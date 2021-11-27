import bcrypt from 'bcrypt';
import express from 'express';
import passport from 'passport';
import {newUser,userRole} from ".././lib/db.js";
import { ensureAuthenticated, forwardAuthenticated } from ".././config/auth.js";
const router = express.Router();

var requireAuth = passport.authenticate('jwt', { session: false }),
  requireLogin = passport.authenticate('local', { session: false });

  function roleAuthorization (roles) {
    return function (req, res, next) {
      var user = req.user;
  
      // User.findById(user._id, function (err, foundUser) {
      //   if (err) {
      //     res.status(422).json({ error: 'No user found.' });
      //     return next(err);
      //   }
  
      //   if (roles.indexOf(foundUser.role) > -1) {
      //     return next();
      //   }
  
      //   res
      //     .status(401)
      //     .json({ error: 'You are not authorized to view this content' });
      //   return next('Unauthorized');
      // });
      return next("nice");
    };
  };

router.use("/protected",async function getUserRoles(req, res, next) {
  req.userRoleNames = [];

  if (req.isAuthenticated()) {
    req.userRoleNames.push('authenticated');
    // console.log("ses",req.session.passport.user)
    if(req.session?.passport?.user){
      const {err,role} = await userRole(req.session.passport.user);
      req.userRoleNames.push(role);
    }
  } else {
    req.userRoleNames.push('unAuthenticated');
    // return next(); // skip role load if dont are authenticated
  }

  // get user roles, you may get roles from DB ...
  // and if are admin add its role
  // console.log(req.session)
  
  // req.userRoleNames.push('administrator');

  next();

});
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
// router.get('/login', forwardAuthenticated, (req, res) => res.render('login'));
// router.get('/register', forwardAuthenticated, (req, res) => res.render('register'));

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
router.post('/register', async(req, res) => {
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
    const  {err,user} = await newUser(name, email, hash);
    if(err){
      res.status(401).send({ msg: err.message });
    }
    else{
      res.status(200).send({msg:"Added"})
    }
    // await register(res, name, email, password, password2).then(() => {
    // })
  }
});
async function hashPassword(password) {
  const saltRounds = 10;

  // bcrypt.hash(password, saltRounds, function(err, hash) {
  //   if(err)throw err;
  //   return hash;
  //     // Store hash in your password DB.
  // });


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
      return res.status(401).send({ message: "Wrong credits" });
    }
    req.logIn(user, function (err) {
      if (err) { return next(err); }
      console.log(req.isAuthenticated())
      return res.status(200).send({ msg: '' });
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
router.post('/comment',passport.authenticate('moderator',{}), async (req, res,next) => {
  console.log(req.body)
  res.status(401).send({ message: "Wrong credits" })
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
  constructor(actionType,message){
    this.actionType = actionType;
    this.message = message;
    this.createTime = new Date();
  }
}
export { router };
