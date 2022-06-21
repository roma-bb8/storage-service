db.createCollection("storage");

db.storage.createIndex({key1: 1, key2: 1}, {name: "keys"});
db.storage.createIndex({createAt: 1}, {name: "createAt"});

db.storage.insertMany([
    {key1: "phone", key2: "+1234567890", value: {"asdf": "asdf"}, createAt: 1655683200},
    {key1: "phone", key2: "+5554567890", value: {"eee": "fff"}, createAt: 1655683200},
    {key1: "email", key2: "email@email.com", value: {"zxc": "hhh"}, createAt: 1655683200},
]);
