import bcrypt from 'bcrypt';
import express from 'express';
import passport from 'passport';
import { commentQueryByPageId, pageQuerySimilarBySameCategory, pageQuerySimilarByTitle, pageQueryLast, pageQueryPopular, logQueryLatest } from ".././lib/db.js";
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
    console.log("/pages", req.body);
    // await filtrPages();
    var documents = []
    if (req.body["title"])
        documents = await pageQuerySimilarByTitle(req.body.title, req.body["offset"], req.body["count"]);
    else if (req.body["category"])
        documents = await pageQuerySimilarBySameCategory(req.body.category, req.body["offset"], req.body["count"]);
    else if (req.body["sort"] == "last")
        documents = await pageQueryLast(req.body["offset"], req.body["count"]);
    else if (req.body["sort"] == "popular")
        documents = await pageQueryPopular(req.body["offset"], req.body["count"]);
    else { }
    res.status(200).send(documents);

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
    var documents = []
    console.log("/comments", req.body)
    if (req.body["pageid"])
        documents = await commentQueryByPageId(req.body.pageid, req.body["offset"], req.body["count"]); //TODO
    else { }
    res.status(200).send(documents);
})
/**
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
/*router.post('/categories', async (req, res, next) => {
    console.log(req.body)
    var documents = []
    if (req.body["categoryid"] > 0){
        // documents = await categoryQueryGetChildreenById(req.body.categoryid, req.body["offset"], req.body["count"])
    }
    // else if (req.body["category"] != "all")
        // documents = await categoryQueryGetChildreen(req.body.category, req.body["offset"], req.body["count"]); //TODO
    else if (req.body["categoryid"] == 0){}
        // documents = await categoryQueryAll(req.body["offset"], req.body["count"]); //TODO
    else { }
    if (documents) {
        documents.map(el => {
            if (el.title.indexOf("Category:") > -1)
                el.title = el.title.substring(9);
        })
    }
    res.status(200).send(documents);
})*/
export { router };