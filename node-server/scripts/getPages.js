import https from 'https';
import querystring from 'querystring';
import { newComment } from ".././lib/db.js";


//  https://en.wikipedia.org/w/api.php?format=json&action=query&prop=categories&pageids=4825956&clcontinue=4825956|Languages_attested_from_the_20th_century
async function pageCategoryRequest(pageid, clcontinue = "") {
  let requestArgs = {
    "action": "query",
    "prop": "categories",
    "format": "json",
    "pageids": pageid
  }
  if (clcontinue != "")
    requestArgs["clcontinue"] = clcontinue;
  if (!pageid) {
    return null
  }
  var requestOptions = {
    hostname: 'en.wikipedia.org',
    port: 443,
    path: `/w/api.php?${querystring.stringify(requestArgs)}`,
    method: 'GET'
  }
  let page = await wikiRequest(requestOptions)
  let newCm;
  if (page["continue"])
    newCm = page["continue"]["clcontinue"];
  else
    newCm = null;
  return [page, newCm];
}
async function commentUserRequest(user, uccontinue = "") {
  let requestArgs = {
    "action": "query",
    "list": "usercontribs",
    "prop": "text",
    "format": "json",
    "ucuser": user
  }
  if (uccontinue != "")
    requestArgs["uccontinue"] = uccontinue;
  if (!user) {
    return null
  }
  var requestOptions = {
    hostname: 'en.wikipedia.org',
    port: 443,
    path: `/w/api.php?${querystring.stringify(requestArgs)}`,
    method: 'GET'
  }
  let page = await wikiRequest(requestOptions)
  let newCm;
  if (page["continue"])
    newCm = page["continue"]["uccontinue"];
  else
    newCm = null;
  return [page, newCm];
}
export async function pageCategries({ "pageid": pageid }) {
  var categories = [];
  var cmcontinue;
  try {
    while (true) {
      let [file, newCm] = await pageCategoryRequest(pageid, cmcontinue)
      console.log(newCm, file, pageid)
      if (!file?.query?.pages[pageid])
        return { "categories": categories };
      let members = file["query"]["pages"][pageid]["categories"];
      if (members == undefined || members === undefined || members == null) {
        return { "categories": categories };
      }
      if(!members){
        return { "categories": categories }; 
      }
      // console.log(members?.length)
      for (let k in members) {
        // let {user,pageid,timestamp,comment} = members[k]
        // await newComment(user, pageid, timestamp,comment)
        categories.push(members[k]);
      }
      if (newCm) {
        cmcontinue = newCm;
      }
      else {
        break;
      }
    }
    return { "categories": categories };
  } catch (e) {
    console.log(e); return { "categories": [] }
  }
}
export async function userComments({ "user": user }) {
  var comments = [];
  var cmcontinue;
  while (true) {
    let [file, newCm] = await commentUserRequest(user, cmcontinue)
    console.log(newCm)
    let members = file["query"]["usercontribs"];
    for (let k in members) {
      let { user, pageid, timestamp, comment } = members[k]
      await newComment(user, pageid, timestamp, comment)
      comments.push(members[k]);
    }
    if (newCm) {
      cmcontinue = newCm;
    }
    else {
      break;
    }
  }
  return { "comments": comments };
}
export async function wikiPageRequest({ "pageid": pageid, "title": title }) {
  let requestArgs = {
    "action": "parse",
    "formatversion": "2",
    "prop": "text",
    "format": "json"
  }
  if (pageid)
    requestArgs["pageid"] = pageid;
  else if (title)
    requestArgs["page"] = title;
  else {
    return null
  }
  var requestOptions = {
    hostname: 'en.wikipedia.org',
    port: 443,
    path: `/w/api.php?${querystring.stringify(requestArgs)}`,
    method: 'GET'
  }
  let page = await wikiRequest(requestOptions)
  return page;
}
// https://en.wikipedia.org/w/api.php?action=parse&format=json&page=Slovenia&prop=text&formatversion=2
async function categoryMemberRequest(categoryName, cmcontinue = "") {
  const requestArgs = {
    "action": "query",
    "list": "categorymembers",
    "prop": "categoryinfo",
    "cmtitle": `Category:${categoryName}`,
    "format": "json",
    "cmcontinue": `${cmcontinue}`
  }
  var requestOptions = {
    hostname: 'en.wikipedia.org',
    port: 443,
    path: `/w/api.php?${querystring.stringify(requestArgs)}`,
    method: 'GET'
  }
  let page = await wikiRequest(requestOptions)
  let newCm;
  if (page["continue"])
    newCm = page["continue"]["cmcontinue"];
  else
    newCm = null;
  return [page, newCm];
}
export async function insideCategory({ "category": categoryName }) {
  var pages = [];
  var categories = [];
  var cmcontinue;
  try {
    while (true) {
      let [file, newCm] = await categoryMemberRequest(`Category:${categoryName}`, cmcontinue)
      let members = file["query"]["categorymembers"];
      for (let k in members) {
        if (members[k]["ns"] == 0)
          pages.push(members[k]);
        if (members[k]["ns"] == 14)
          categories.push(members[k]);
        if (members[k]["ns"] == 1) {
          return { "pages": pages, "categories": categories };
        }
      }
      if (newCm) {
        cmcontinue = newCm;
      }
      else {
        break;
      }
      if (pages.length > 100 || categories.length > 100) {
        return { "pages": pages, "categories": categories };
      }
    }
    return { "pages": pages, "categories": categories };
  } catch (e) { console.log("insidecategory", e) }
}
async function doSomethingUseful() {
  // return the response
  do {
    var file = await doRequest(options, data);
    console.log(file["continue"])
    console.log(file["query"]["categorymembers"])
    if (file["continue"]) {
      args["cmcontinue"] = file["continue"]["cmcontinue"]
      options["path"] = `/w/api.php?${querystring.stringify(args)}`
    } else {
      break;
    }


  } while (1)
  // https://en.wikipedia.org/w/api.php?action=parse&format=json&page=Slovenia&prop=text&formatversion=2
  // console.log( await doRequest(options, data));
}
/**
 * Do a request with options provided.
 *
 * @param {Object} options
 * @param {Object} data
 * @return {Promise} a promise of request
 */
function doRequest(options, data) {
  return new Promise((resolve, reject) => {
    const req = https.request(options, (res) => {
      res.setEncoding('utf8');
      let responseBody = '';

      res.on('data', (chunk) => {
        responseBody += chunk;
      });

      res.on('end', () => {
        console.log(responseBody)
        resolve(JSON.parse(responseBody));
      });
    });

    req.on('error', (err) => {
      reject(err);
    });

    req.write(data)
    req.end();
  });
}
/**
 * Do a request with options provided.
 *
 * @param {Object} options
 * @return {Promise} a promise of request
 */
function wikiRequest(options) {
  return new Promise((resolve, reject) => {
    const req = https.request(options, (res) => {
      res.setEncoding('utf8');
      let responseBody = '';

      res.on('data', (chunk) => {
        responseBody += chunk;
      });

      res.on('end', () => {
        try {
          let a = JSON.parse(responseBody);
          resolve(a);
      } catch(e) {
          console.log("JSON error");
         resolve({})
      }
        
      });
    });

    req.on('error', (err) => {
      reject(err);
    });
    req.write("")
    req.end();
  });
}
// doSomethingUseful();
// let {pages,categories} = await insideCategory("Countries_in_Europe")
// console.log(categories);
// let page  = await  wikiPageRequest({title:"Poland"})
// let page  = await  wikiPageRequest({pageid:22936})
// console.log(page);