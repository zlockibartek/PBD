import  express from 'express';
const router = express.Router();
import  { ensureAuthenticated, forwardAuthenticated } from '../config/auth.js';
// router.get('/', forwardAuthenticated, (req, res) => res.render('welcome'));

// Dashboard
router.get('/dashboard', ensureAuthenticated, (req, res) =>
  res.render('dashboard', {
    user: req.user
  })
);
export {router};
