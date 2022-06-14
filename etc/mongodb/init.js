/* sudo su :) */
db = db.getSiblingDB("admin");
db.auth(_getEnv("MONGO_INITDB_ROOT_USERNAME"), _getEnv("MONGO_INITDB_ROOT_PASSWORD"));

/* create databases and user */
db = db.getSiblingDB(_getEnv("MONGO_DATABASE"));
db.createUser({
    user: _getEnv("MONGO_USERNAME"),
    pwd: _getEnv("MONGO_PASSWORD"),
    roles: [
        {
            role: "dbOwner",
            db: _getEnv("MONGO_DATABASE")
        }
    ]
});

/* init databases */
db.createCollection("storage");

db.storage.createIndex({key1: 1, key2: 1}, {name: "keys"});
db.storage.createIndex({createAt: 1}, {name: "createAt"});

db.storage.insertMany([
    {key1: "phone", key2: "+1234567890", value: {"asdf": "asdf"}, createAt: 1655683200},
    {key1: "phone", key2: "+5554567890", value: {"eee": "fff"}, createAt: 1655683200},
    {key1: "email", key2: "email@email.com", value: {"zxc": "hhh"}, createAt: 1655683200}
]);
