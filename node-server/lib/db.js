import * as assert from "assert";
import { SSL_OP_SSLEAY_080_CLIENT_DH_BUG } from "constants";
import { MongoClient, ObjectId } from "mongodb";
import secretConfig from "../config/keys.json";
const { dbURL: url, dbName, collections } = secretConfig;
import * as  errors from "../lib/errors.js"

/**
 * A class providing a shared connection to a database  https://en.wikipedia.org/w/api.php?format=json&action=query&prop=categories&pageids=4825956&clcontinue=4825956|Languages_attested_from_the_20th_century
 */
export class DBManager {
	constructor() {
		this.client = new MongoClient(url);
		this._db = null;
	}
	/**
	 * Initialises the database connection.
	 */
	async init() {
		await this.client.connect();
		this._db = this.client.db(dbName);
	}
	/**
	 * Returns the `Db` object. Connects to the database if the client got disconnected.
	 * @returns The DB specified in the config
	 */
	async db() {
		if (!this.client.topology?.isConnected()) {
			await this.init();
		}
		assert.ok(this._db !== null, "The _db can't be null after calling init().");
		return this._db;
	}
}
const dbManager = new DBManager();
const db = await dbManager.db();
const comments = db.collection(collections.comments);
const forums = db.collection(collections.forums);
const users = db.collection(collections.users);
const pages = db.collection(collections.pages);
const logs = db.collection(collections.logs);
export async function commentQueryByPageId(pageid, skip = 0, limit = 50) {

	// const db = await dbManager.db();
	// const comments = db.collection(collections.comments);
	// const forums = db.collection(collections.forums);
	const forumId = await forums.findOne({ "pageid": pageid })
	const query = await comments.find({ forumId: forumId?._id.toString() })
	let result = await query.sort({ timestamp: -1 }).skip(skip).limit(limit).toArray()
	result = result.map(({ eamil, text, timestamp, attachements }) => ({ eamil, text, timestamp, attachements }))
	return result;
}
export async function newComment(username, pageid, comment, attachements) {
	try {
		// const db = await dbManager.db();
		// const comments = db.collection(collections.comments);
		// const forums = db.collection(collections.forums);
		// const user = db.collection(collections.users);
		if (await forums.findOne({ pageid: pageid }) == null) {
			await newForum(pageid);
			// throw new errors.Forum(`Forum: $forumId do not exist in database`);
		}
		const a = await forums.findOne({ pageid: pageid })
		const comm = new Comment(username, a?._id.toString(), comment, attachements)
		await newLog("new comment", `Added new comment to page:${pageid} with message: ${comment}`)
		await comments.insertOne(comm);
		let b = await forums.findOneAndUpdate({ _id: ObjectId(comm.forumId) }, { $inc: { commentCount: 1 }, $set: { lastUpdate: comm.timestamp } })
		console.log(b)

	} catch (e) {
		return { err: e };
	}
}
export async function deleteComment(timestamp) {
	// let result1 = await comments.fin({"timestamp":timestamp});
	let result = await comments.findOneAndDelete({"timestamp":timestamp});
	console.log(result)
}
export async function newUser(name, email, password) {
	try {
		// const db = await dbManager.db();
		// const users = db.collection(collections.users);
		if (await users.findOne({ email: email }) !== null) {
			throw new errors.User(`User with email: ${email} exists in database`);
		}
		newLog("new user", `Create user with email:${email}`)
		let val = await users.insertOne(new User(name, email, password))
		return { user: val }
	} catch (e) {
		return { err: e };
	}
}
export async function userRole(id) {
	try {
		// const db = await dbManager.db();
		// const col = db.collection(collections.users);
		const us = await users.findOne({ _id: ObjectId(id) })
		if (!us) {
			throw new errors.User(`Empty user`);
		} else {
			return { role: us["role"] }
		}
	} catch (e) {
		return { err: e };
	}
}
export async function userName(id) {
	// const db = await dbManager.db();
	// const col = db.collection(collections.users);
	const us = await users.findOne({ _id: ObjectId(id) })
	if (!us) {
		return null
	} else {
		return { username: us["email"] }
	}
}
export async function newPage(title, pageId, category) {
	try {
		// const db = await dbManager.db();
		// const col = db.collection(collections.pages);
		if (await pages.findOne({ pageId: pageId }) !== null) {
			return null;
			// throw new errors.Page(`Page $pageId exists in database`);
		}

		await pages.insertOne(new Page(title, pageId, category))
	}
	catch (e) {
		console.log(e)
		return e;
	}
}
export async function logQueryLatest(skip = 0, limit = 50) {
	// const db = await dbManager.db();
	// const forums = db.collection(collections.forums);
	const query = await logs.find({});
	const result = await query.sort({ createTime: -1 }).skip(skip).limit(limit)
	return result.toArray()
	
}

export async function newLog(actiontype, message) {
	// const db = await dbManager.db();
	// const logs = db.collection(collections.logs);
	await logs.insertOne(new Log(actiontype, message))
}
/*export async function pageQueryTitle(title, skip = 0, limit = 50) {
	try {
		const db = await dbManager.db();
		const col = db.collection(collections.pages);
		const query = await col.find({ title: { $regex: title } })
		const result = await query.sort({ title: 1 }).skip(skip).limit(limit).toArray()
		result.forEach(element => { delete element._id });
		if (err)
			throw new errors.Page(`Page $pageId exists in database`);
		return result

		// await col.insertOne(new Page(title,pageId,category))
	}
	catch (e) {
		console.log(e)
		return e;
	}
}*/
export async function pageQueryCategory(category, skip = 0, limit = 50) {
	try {
		// const db = await dbManager.db();
		// const pages = db.collection(collections.pages);
		const query = await pages.find({ parentCategory: category })
		const result = await query.sort({ title: 1 }).skip(skip).limit(limit).toArray()
		result.forEach(element => { delete element._id });
		// if(err)
		// throw new errors.Page(`Page $pageId exists in database`);
		return result
		// await col.insertOne(new Page(title,pageId,category))
	}
	catch (e) {
		console.log(e)
		return e;
	}
}
export async function filtrPages() {
	try {
		const groups = await comments.aggregate([
			{ "$group": { _id: "$forumId" } }
		])
		// await forums.deleteMany({commentCount:0})
		let query = await forums.find({}).toArray()
		for (let k in query) {
			let el = query[k]
			const page = await pages.findOne({ pageId: el?.pageid })
			if (page == null) {
				await forums.deleteOne({ _id: ObjectId(el._id) })
			}
		}

		// }
		// await query.forEach(async function (el) {
		// 	const page = await pages.findOne({pageId:el?.pageid})
		// 	if(!await pages.findOne({pageId:el?.pageid})){
		// 		await forums.deleteOne({_id:ObjectId(el._id)})
		// 	}
		// })
		await groups.forEach(async function (el) {
			let pageid = await forums.findOne(ObjectId(el._id))

			// const page = await pages.findOne({ pageId: pageid?.pageid })
			if (!await pages.findOne({ pageId: pageid?.pageid })) {
				// comments.deleteMany({ forumId: el._id })
				// forums.deleteOne({ _id: ObjectId(el._id) })
				console.log("emptypage", page)
			}
			// let x = await forums.updateOne({ _id: ObjectId(el._id) }, { $set: { "lastUpdate": el.date } })
		})
		const result = await query.sort({ title: 1 }).skip(skip).limit(limit).toArray()
		result.forEach(element => { delete element._id });
		// if(err)
		// throw new errors.Page(`Page $pageId exists in database`);
		return result
		// await col.insertOne(new Page(title,pageId,category))
	}
	catch (e) {
		console.log(e)
		return e;
	}
}
/*export async function pageQueryLast1(skip = 0, limit = 50) {
	try {
		const db = await dbManager.db();
		const col = db.collection(collections.pages);
		const comments = db.collection(collections.comments);
		const forums = db.collection(collections.forums);
		const query = await comments.find();
		// const groups = await comments.aggregate([
		// 	{ "$group": { _id: "$forumId", date: { $last: "$timestamp" } } }
		// ])//.toArray()
		// await groups.forEach(async function (el) {
		// 	let x = await forums.updateOne({ _id: ObjectId(el._id) }, { $set: { "lastUpdate": el.date } })
		// })
		// let com = await query.map( function(p) { return p.createTime; } ).limit(limit).toArray()
		// await comments.find().forEach(async function (el) {
		// 	await comments.updateOne({_id:el._id}, { $set: { "timestamp": new Date(el.createTime).getTime() } })
		// });Category:2021 crimes in New Zealand
		// let res = await categoryQueryBySameParent("2021 crimes in New Zealand")
		const result = await query.sort({ timestamp: 1 }).skip(skip).limit(limit).toArray()
		let ids = result.map(element => { return element.forumId });
		// var ids = ['512d5793abb900bf3e20d012', '512d5793abb900bf3e20d011'];
		var obj_ids = ids.map(function (id) { return ObjectId(id); });
		const forum = await forums.find({ _id: { $in: obj_ids } }).toArray();
		obj_ids = forum.map(function (page) { return page.pageid; });
		const pages = await col.find({ pageId: { $in: obj_ids } }).toArray();
		// if(err)
		// throw new errors.Page(`Page $pageId exists in database`);
		return pages;
		// await col.insertOne(new Page(title,pageId,category))
	}
	catch (e) {
		console.log(e)
		return e;
	}
}*/
async function forumQueryPopular(skip = 0, limit = 50, toArray = true) {
	// const db = await dbManager.db();
	// const forums = db.collection(collections.forums);
	const query = await forums.find({});
	const result = await query.sort({ commentCount: -1 }).skip(skip).limit(limit)
	if (toArray)
		return result.toArray()
	else {
		return result
	}
}
export async function pageQueryLast(skip = 0, limit = 50) {
	const lastForums = await forumQueryLatest(skip, limit, true);
	const lastPageIds = lastForums.map(el => el.pageid)
	// const db = await dbManager.db();
	// const pages = db.collection(collections.pages);
	let result = await pages.find({ pageId: { $in: lastPageIds } }).toArray()
	result = result.sort(function (a, b) {
		return lastPageIds.indexOf(a.pageId) - lastPageIds.indexOf(b.pageId)
	})
	result.forEach(element => { delete element._id })
	return result;
}
export async function pageQueryPopular(skip = 0, limit = 50) {
	const lastForums = await forumQueryPopular(skip, limit, true);
	const lastPageIds = lastForums.map(el => el.pageid)
	// const db = await dbManager.db();
	// const pages = db.collection(collections.pages);
	let result = await pages.find({ pageId: { $in: lastPageIds } }).toArray() //should be left join
	result = result.sort(function (a, b) {
		return lastPageIds.indexOf(a.pageId) - lastPageIds.indexOf(b.pageId)
	})
	result.forEach(element => { delete element._id });;
	return result
}
async function forumQueryLatest(skip = 0, limit = 50, toArray = true) {
	// const db = await dbManager.db();
	// const forums = db.collection(collections.forums);
	const query = await forums.find({});
	const result = await query.sort({ lastUpdate: -1 }).skip(skip).limit(limit)
	if (toArray)
		return result.toArray()
	else {
		return result
	}
}
export async function pageQuerySimilarByTitle(title, skip = 0, limit = 50) {
	// const db = await dbManager.db();
	// const pages = db.collection(collections.pages);
	newLog("title search", `Searched page with:${title}`)
	const query = await pages.find({ title: { $regex: title } })
	const result = await query.sort({ title: 1 }).skip(skip).limit(limit).toArray()
	result.forEach(element => { delete element._id })
	return result;

}
export async function pageQuerySimilarBySameCategory(category, skip = 0, limit = 50) {
	// const db = await dbManager.db();
	// const pages = db.collection(collections.pages);
	newLog("category search", `Searched page with:${title}`)
	const query = await pages.find({ parentCategory: category })
	const result = await query.sort({ title: 1 }).skip(skip).limit(limit).toArray()
	result.forEach(element => { delete element._id })
	return result;

}
/* async function categoryQueryBySameParent(category) {
	const db = await dbManager.db();
	const categories = db.collection(collections.categories);

	const result = await categories.findOne({ childreen: { $elemMatch: { title: { $regex: category } } } })
	// const result = await query.sort().skip(skip).limit(limit)
	return result
}
export async function categoryQueryAll(skip = 0, limit = 50, toArray = true) {
	const db = await dbManager.db();
	const categories = db.collection(collections.categories);
	const query = await categories.find({})
	const result = await query.sort().skip(skip).limit(limit).toArray()
	result.forEach(element => { delete element._id; delete element.childreen })
	return result;
}
export async function categoryQueryGetChildreen(title) {
	const db = await dbManager.db();
	const categories = db.collection(collections.categories);
	const result = await categories.findOne({ title: title })
	return result?.childreen
	// const result = await query.sort().skip(skip).limit(limit)
}
export async function categoryQueryGetChildreenById(pageid) {
	const db = await dbManager.db();
	const categories = db.collection(collections.categories);
	let result = await categories.findOne({ pageId: pageid })
	result = result?.childreen;
	if (result) {
		result.map(el => {
			el["pageId"] = el["pageid"];
			delete el.pageid;

		})
	}
	return result
	// const result = await query.sort().skip(skip).limit(limit)
}

export async function newCategory(pageId, title, childreen) {
	try {
		const db = await dbManager.db();
		const col = db.collection(collections.categories);
		// console.log("a",await col.findOne({ pageId: pageId}))
		if (await col.findOne({ pageId: pageId }) !== null) {
			return null;
			// throw new errors.Page(`Category $pageId exists in database`);
		}

		var x = await col.insertOne(new Category(title, pageId, childreen))
		console.log(x);
	}
	catch (e) {
		console.log(e);
		return e;
	}
}
*/
export async function newForum(pageId) {
	try {
		// const db = await dbManager.db();
		// const forums = db.collection(collections.forums);
		// const page = db.collection(collections.pages);
		// if (await page.findOne({ pageId: pageId }) == null) {
		// 	throw new errors.Forum(`There is no page $pageId in database`);
		// }
		const forum = await forums.findOne({ pageid: pageId })
		if (forum != null) {
			throw new errors.Forum(`Forum to page: $pageId exists in database`);
		}
		await forums.insertOne(new Forum(pageId))
	} catch (e) {
		return e;
	}
}

class User {

	constructor(name, email, password) {
		// this.test = test;
		this.name = name;
		this.email = email
		this.password = password;
		this.createTime = new Date();
		this.role = "user";
	}
}
class Comment {
	constructor(email, forumId, text, attachements = []) {
		this.eamil = email;
		this.forumId = forumId;
		this.text = text;
		this.attachements = attachements;
		// if (timestamp)
		// this.createTime = timestamp;
		// else/
		this.timestamp = new Date().getTime();
	}
}
class Page {
	constructor(title, pageId, category) {
		this.pageId = pageId;
		this.title = title;
		this.parentCategory = category;
		this.createTime = new Date();
	}
}
class Category {
	constructor(title, pageId, chlidreen) {
		this.pageId = pageId;
		this.title = title
		this.childreen = chlidreen;
	}
}
class Forum {
	constructor(pageid) {
		this.pageid = pageid;
		this.commentCount = 0;
		this.filesCount = 0;
		this.lastUpdate = 0;
		this.lastCommentId = "";
		this.timestamp = new Date().getTime();
	}
}
class Log {
	constructor(actionType, message) {
		this.actionType = actionType;
		this.message = message;
		this.createTime = new Date().getTime();
	}
}
/**
 * Connection to the database specified in config. Use the `.db()` method to get a `Db` object.
 */


// export dbManager;
