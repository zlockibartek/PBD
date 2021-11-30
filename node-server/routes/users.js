import bcrypt from 'bcrypt';
import express from 'express';
import passport from 'passport';
import { newUser, userRole, newComment, newPage, userName, logQueryLatest, deleteComment } from ".././lib/db.js";
import { ensureAuthenticated, forwardAuthenticated } from ".././config/auth.js";
const router = express.Router();
import bodyParser from "body-parser"
// import * as pages from "./scripts/getPages.js"
import busboy from 'connect-busboy'; //middleware for form/file upload
// var path = require('path');     //used for file path
import fs from 'fs-extra';
import * as pages from ".././scripts/getPages.js"
router.use(["/protected", "/comment", "/logs", '/deletecomment'], async function getUserRoles(req, res, next) {
  req.userRoleNames = [];
  if (req.isAuthenticated()) {
    if (req.session?.passport?.user) {
      const { err, role } = await userRole(req.session.passport.user);
      req.userRoleNames.push(role);
    }
  } else { }

  next();

});
/**
 * @swagger
 * /users/upload:
 *   post:
 *       descripton: Uploads a file.
 *       consumes: 
 *          - multipart/form-data
 *       parameters:
 *          - in: formData
 *            name: upfile
 *            type: file
 *       responses:
 *           200:
 *               description: Sucesfull login
 *           401:
 *               description: Cant login
 *               content:
 *                   application/json:
 *               schema:
 *                   type: object
 */
router.post('/upload', function (req, res, next) {
  var fstream;
  req.pipe(req.busboy);
  console.log(req["files"])
  req.busboy.on('file', function (fieldname, file, filename) {
    fstream = fs.createWriteStream('node-server/img/' + filename);
    file.pipe(fstream);
    fstream.on('close', function () {
      console.log("Upload Finished of " + filename);
      res.send(true);           //where to go next
    });
  });
});
/**
 * @swagger
 * /users/logs:
 *   post:
 *       descripton: Returns list of pages.
 *       content: 
 *          application/x-www-form-urlencoded
 *       parameters:
 *          - in: body
 *            name: aa
 *            schema:
 *              $ref: '#/definitions/Querylog'
 *       responses:
 *           200:
 *               description: Sucesfull login
 *           401:
 *               description: Cant login
 *               content:
 *                   application/json:
 *               schema:
 *                   type: object
 * */
router.post('/logs', async (req, res, next) => {
  var documents = []
  console.log("/logs", req.body)
  if (req.userRoleNames.indexOf("moderator") > -1)
    documents = await logQueryLatest(req.body["offset"], req.body["count"]); //TODO
  res.status(200).send(documents);
})
/**
* @swagger
* /users/protected:
*   get:
*       descripton: Returns list of stops in path and total distance.
*       content: 
*          application/x-www-form-urlencoded
*       parameters:
*       responses:
*           200:
*               description: Sucesfull login
*           401:
*               description: Cant login
*               content:
*                   application/json:
*               schema:
*                   type: object
*/
router.get('/protected', function (req, res) {
  // if(req.userRoleNames[])
  console.log(req.userRoleNames);
  // console.log(req.session);
  res.send({ content: 'Success' });
});
/**
 * @swagger
 * /users/register:
 *   post:
 *       descripton: Returns list of stops in path and total distance.
 *       content: 
 *          application/x-www-form-urlencoded
 *       parameters:
 *          - in: body
 *            name: aa
 *            schema:
 *              $ref: '#/definitions/User'
 *       responses:
 *           200:
 *               description: Sucesfull login
 *           401:
 *               description: Cant login
 *               content:
 *                   application/json:
 *               schema:
 *                   type: object
 */
router.post('/register', async (req, res) => {
  const { email, password } = req.body;
  const name = "a";
  const password2 = password;
  let errors = [];

  if (!name || !email || !password || !password2) {
    errors.push({ msg: 'Please enter all fields' });
  }

  else if (password != password2) {
    errors.push({ msg: 'Passwords do not match' });
  }

  else if (password.length < 6) {
    errors.push({ msg: 'Password must be at least 6 characters' });
  }
  else { }
  if (errors.length > 0) {
    res.status(401).send(errors)
  }
  else {
    const hash = await hashPassword(password);
    const { err, user } = await newUser(name, email, hash);
    if (err) {
      res.status(401).send({ msg: err.message });
    }
    else {
      res.status(200).send({ msg: "Added" })
    }
  }
});
async function hashPassword(password) {
  const saltRounds = 10;

  const hashedPassword = await new Promise((resolve, reject) => {
    bcrypt.hash(password, saltRounds, function (err, hash) {
      if (err) reject(err)
      resolve(hash)
    });
  })

  return hashedPassword
}
// Login
/**
 * @swagger
 * /users/login:
 *   post:
 *       descripton: Returns list of stops in path and total distance.
 *       content: 
 *          application/x-www-form-urlencoded
 *       parameters:
 *          - in: body
 *            name: aa
 *            schema:
 *              $ref: '#/definitions/User'
 *       responses:
 *           200:
 *               description: Sucesfull login
 *           401:
 *               description: Cant login
 *               content:
 *                   application/json:
 *               schema:
 *                   type: object
 */
router.post('/login', (req, res, next) => {
  passport.authenticate('local', function (err, user, info) {
    console.log(user)
    console.log(user, err, req.session);
    if (err) { return next(err); }
    if (!user) {
      return res.status(401).send([]);
    }
    req.logIn(user, async function (err) {
      if (err) { return next(err); }
      console.log(req.isAuthenticated())
      const { _, role } = await userRole(req?.session?.passport?.user);
      return res.status(200).send([role]);
    });
  })(req, res, next);
});
/**
 * @swagger
 * /users/logout:
 *   post:
 *       descripton: Returns list of stops in path and total distance.
 *       content: 
 *          application/x-www-form-urlencoded
 *       responses:
 *           200:
 *               description: Sucesfull logout
 */
router.post('/logout', (req, res) => {
  req.logout();
  req.res.status(200).send({ msg: '' });
});
/**
 * @swagger
 * /users/comment:
 *   post:
 *       descripton: Returns list of stops in path and total distance.
 *       content: 
 *          application/x-www-form-urlencoded
 *       parameters:
 *          - in: body
 *            name: aa
 *            schema:
 *              $ref: '#/definitions/Comment'
 *       responses:
 *           200:
 *               description: Sucesfull login
 *           401:
 *               description: Cant login
 *               content:
 *                   application/json:
 *               schema:
 *                   type: object
 */
router.post('/comment', async (req, res, next) => {
  console.log("/comment", req.body)
  console.log(req.rawHeaders)
  const { email, pageid, text, attachements } = req.body;
  console.log(req.userRoleNames);
  if (req.userRoleNames.indexOf("user") > -1) {
    if (req.session?.passport?.user) {
      let { username } = await userName(req.session?.passport?.user)
      console.log(username)
      await newComment(username, pageid, text, attachements)
      res.status(201).send(true)
    }
  }
  else {
    res.status(201).send(false)
  }
  // } else
  // res.send({ message: "No permisions" });
});
/**
 * @swagger
 * /users/deletecomment:
 *   post:
 *       descripton: Returns list of stops in path and total distance.
 *       content: 
 *          application/x-www-form-urlencoded
 *       parameters:
 *          - in: body
 *            name: aa
 *            schema:
 *              $ref: '#/definitions/Deletecomment'
 *       responses:
 *           200:
 *               description: Sucesfull login
 *           401:
 *               description: Cant login
 *               content:
 *                   application/json:
 *               schema:
 *                   type: object
 */
router.post('/deletecomment', async (req, res, next) => {
  console.log("/deletecomment", req.body)
  console.log(req.rawHeaders)
  const { timestamp } = req.body;
  console.log(req.userRoleNames);
  if (req.userRoleNames.indexOf("moderator") > -1) {
    // let { username } = await userName(req.session?.passport?.user)
    // console.log(username)
    await deleteComment(timestamp)
    res.status(201).send(true)
  }
  else {
    res.status(201).send(false)
  }
  // } else
  // res.send({ message: "No permisions" });
});
/**
 * @swagger
 * /users/page:
 *   post:
 *       descripton: Returns list of stops in path and total distance.
 *       content: 
 *          application/x-www-form-urlencoded
 *       parameters:
 *          - in: body
 *            name: aa
 *            schema:
 *              $ref: '#/definitions/Wikipage'
 *       responses:
 *           200:
 *               description: Sucesfull login
 *           401:
 *               description: Cant login
 *               content:
 *                   application/json:
 *               schema:
 *                   type: object
 */
router.post('/page', async (req, res, next) => {
  console.log(req.body)
  console.log(req.rawHeaders)
  const page = await pages.wikiPageRequest(req.body);
  console.log(page);
  if (!page["error"]) {
    let status = await newPage(page.parse.title, page.parse.pageid, "")
    // await uploadPages({ title: "", pages: [page] })
    if (status == null)
      res.status(201).send(false);
    else
      res.status(201).send(true);
  } else {
    res.status(201).send(false)
  }
  // const { email, pageid, text, attachements } = req.body;
  // console.log(req.userRoleNames);
  // if (req.userRoleNames.indexOf("unAuthenticated") < 0){
  //   // await newComment(email, pageid, text, attachements)
  //   res.status(201).send({ message: req.userRoleNames })
  // }else 
  //   res.send({ message: "No permisions" });
});


class User {

  constructor(name, email, password) {
    // this.test = test;
    this.name = name;
    this.email = email
    this.password = password;
    this.createTime = new Date();
  }
}
class Comment {
  constructor(userId, forumId, text, attachments) {
    this.userId = userId;
    this.forumId = forumId;
    this.text = text;
    this.attachements = attachements
  }
}
class Page {
  constructor(url, pageId, categories) {
    this.url = url;
    this.pageId = pageId;
    this.categories = categories;
  }
}
class Forum {
  constructor(pageId) {
    this.pageId = pageId;
    this.commentCount = 0;
    this.filesCount = 0;
    this.lastUpdate = 0;
    this.lastCommentId = "";
    this.createTime = new Date();
  }
}
class Log {
  constructor(actionType, message) {
    this.actionType = actionType;
    this.message = message;
    this.createTime = new Date();
  }
}
export { router };
