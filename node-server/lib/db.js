import * as assert from "assert";
import { MongoClient,ObjectId } from "mongodb";
import secretConfig from "../config/keys.json";
import * as  errors from "../lib/errors.js"
const { dbURL: url, dbName, collections } = secretConfig;

/**
 * A class providing a shared connection to a database
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
export async function newComment(email, forumId, message, attachments) {
	try {
		const db = await dbManager.db();
		const col = db.collection(collections.comment);
		const forum = db.collection(collections.forums);
		const user = db.collection(collections.users);
		if (await forum.findOne({ forumId: forumId }) == undefined) {
			throw new errors.Forum(`Forum: $forumId do not exist in database`);
		}
		if (await user.forum({ email: email }) == undefined) {
			throw new errors.User(`User with email: $email exists in database`);
		}
		const comm = new Comment(email, forumId, message, attachements)
		await col.insertOne(comm);
		await forum.findOneAndUpdate({ forumId: forumId }, { $inc: { commentCount: 1 }, lastUpdate: comm.createTime })

	} catch (e) {
		return { err: e };
	}
}
export async function newUser(name, email, password) {
	try {
		const db = await dbManager.db();
		const col = db.collection(collections.users);
		if (await col.findOne({ email: email }) !== null) {
			throw new errors.User(`User with email: ${email} exists in database`);
		}
		let val = await col.insertOne(new User(name, email, password))
		return {user:val}
	} catch (e) {
		return { err: e };
	}
}
export async function userRole(id) {
	try {
		const db = await dbManager.db();
		const col = db.collection(collections.users);
		const us = await col.findOne({ _id: ObjectId(id) })
		if (!us) {
			throw new errors.User(`Empty user`);
		} else {
			return { role: us["role"] }
		}
	} catch (e) {
		return { err: e };
	}
}
export async function newPage(title, pageId, category) {
	try {
		const db = await dbManager.db();
		const col = db.collection(collections.pages);
		if (await col.findOne({ pageId: pageId }) !== null) {
			throw new errors.Page(`Page $pageId exists in database`);
		}
		await col.insertOne(new Page(title, pageId, category))
	}
	catch (e) {
		console.log(e)
		return e;
	}
}
export async function pageQueryTitle(title, skip = 0, limit = 50) {
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
}
export async function pageQueryCategory(category, skip = 0, limit = 50) {
	try {
		const db = await dbManager.db();
		const col = db.collection(collections.pages);
		const query = await col.find({ parentCategory: category })
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
export async function newCategory(pageId, title, childreen) {
	try {
		const db = await dbManager.db();
		const col = db.collection(collections.categories);
		// console.log("a",await col.findOne({ pageId: pageId}))
		if (await col.findOne({ pageId: pageId }) !== null) {
			throw new errors.Page(`Category $pageId exists in database`);
		}

		var x = await col.insertOne(new Category(title, pageId, childreen))
		console.log(x);
	}
	catch (e) {
		console.log(e);
		return e;
	}
}
export async function newForum(pageId) {
	try {
		const db = await dbManager.db();
		const col = db.collection(collections.forums);
		const page = db.collection(collections.pages);
		if (await page.findOne({ pageId: pageId }) == undefined) {
			throw new errors.Forum(`There is no page $pageId in database`);
		}
		if (await col.findOne({ pageId: pageId }) !== undefined) {
			throw new errors.Forum(`Forum to page: $pageId exists in database`);
		}
		await col.insertOne(new Forum(pageId))
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
	constructor(email, forumId, text, attachments) {
		this.eamil = email;
		this.forumId = forumId;
		this.text = text;
		this.attachements = attachements;
		this.createTime = new Date();
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
		this.url = url;
		this.pageId = pageId;
		this.title = title
		this.childreen = chlidreen;
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
/**
 * Connection to the database specified in config. Use the `.db()` method to get a `Db` object.
 */
const dbManager = new DBManager();

// export dbManager;
