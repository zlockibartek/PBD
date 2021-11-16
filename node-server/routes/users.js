import express from 'express';
const router = express.Router();
import bcrypt from 'bcrypt';
import passport from 'passport';
import { forwardAuthenticated } from '../config/auth.js';
import dbManager from ".././lib/db.js";

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
 *          - in: query
 *            name: email
 *            default: a@a
 *            required: true
 *            schema:
 *              type: string
 *          - in: query
 *            name: password
 *            default: PH6bGEXDTd6RZ8h
 *            required: true
 *            schema:
 *              type: string
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
router.post('/register', (req, res) => {
  const { email, password } = req.query;
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
    register(res, name, email, password, password2).then(() => {
    })
  }
});
async function register(res, name, email, password, password2) {
  let errors = []
  try {
    const db = await dbManager.db();
    const userCollection = db.collection(`Users`);
    if (await userCollection.findOne({ email: email }) != null) {
      errors.push({ msg: 'Email already exists' });
      res.status(401).send(errors)
    }
    else {
      const newUser = new User(
        name,
        email,
        password
      );
      const hash = await hashPassword(newUser);
      newUser.password = hash;
      await userCollection.insertOne(newUser);
      await res.status(200).send({ msg: "Succesfully created" });
      return 0;
    }
  } catch (e) {
    console.log("e", e);
  }
}
async function hashPassword(user) {

  const password = user.password
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
 *          - in: query
 *            name: email
 *            default: a@a
 *            required: true
 *            schema:
 *              type: string
 *          - in: query
 *            name: password
 *            default: PH6bGEXDTd6RZ8h
 *            required: true
 *            schema:
 *              type: string
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
class User {

  constructor(name, email, password) {
    // this.test = test;
    this.name = name;
    this.email = email
    this.password = password;
    this.createTime = new Date();
  }
}
export { router };
