# Basic Localstorage

A class that holds key values ​​that are easy to use.



## Basic use 
```
include 'localstorage.php'
 $Storage = new LocalStorage();
 // Set
 $Storage->set('test');
 // Get
$Storage->get('test');
// Delete
$Storage->delete('test');
// All
$Storage->all();
// Get All Json
$Storage->getalljson();
// Clear
$Storage->clear();

 
```


## Notice $destroytime
```
If you don't extend the time, the folder and the file inside will be deleted automatically when the class runs within 12 hours.
```
