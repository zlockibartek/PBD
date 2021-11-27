import bcrypt from 'bcrypt';
import express from 'express';
import passport from 'passport';
import {pageQueryCategory, pageQueryTitle} from ".././lib/db.js";
// import { ensureAuthenticated, forwardAuthenticated } from ".././config/auth.js";
const router = express.Router();
/**
 * @swagger
 * /query/pages:
 *   post:
 *       descripton: Returns list of pages.
 *       content: 
 *          application/x-www-form-urlencoded
 *       parameters:
 *          - in: body
 *            name: a
 *            schema:
 *              $ref: '#/definitions/Querypage'
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
router.post('/pages', async (req, res, next) => {
    console.log(req.body);
    var documents = []
    if(req.body["title"])
        documents = await pageQueryTitle(req.body.title,req.body["offset"],req.body["count"]);
    else if(req.body["category"])
        documents = await pageQueryCategory(req.body.category,req.body["offset"],req.body["count"]);
    else {}
    res.status(200).send(documents);
    // try{
    //   const page = await pages.wikiPageRequest(req.body);
    //   console.log(page);
    //   if (!page){
    //     res.status(200).send(page);
    //   }
    //   else
    //     res.status(401).send(page);
    // }catch(e){next(e)}

})
/**
 * @swagger
 * /query/comments:
 *   post:
 *       descripton: Returns list of pages.
 *       content: 
 *          application/x-www-form-urlencoded
 *       parameters:
 *          - in: body
 *            name: a
 *            schema:
 *              $ref: '#/definitions/Querycomment'
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
router.post('/comments', async (req, res, next) => {
    console.log(req.body);
    res.status(200).send("");
    // try{
    //   const page = await pages.wikiPageRequest(req.body);
    //   console.log(page);
    //   if (!page){
    //     res.status(200).send(page);
    //   }
    //   else
    //     res.status(401).send(page);
    // }catch(e){next(e)}

})
/**
 * @swagger
 * /query/categories:
 *   post:
 *       descripton: Returns list of pages.
 *       content: 
 *          application/x-www-form-urlencoded
 *       parameters:
 *          - in: body
 *            name: a
 *            schema:
 *              $ref: '#/definitions/Querycategory'
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
router.post('/categories', async (req, res, next) => {
    console.log(req.body);
    res.status(200).send("");
    // try{
    //   const page = await pages.wikiPageRequest(req.body);
    //   console.log(page);
    //   if (!page){
    //     res.status(200).send(page);
    //   }
    //   else
    //     res.status(401).send(page);
    // }catch(e){next(e)}

})
export { router };