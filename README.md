# Yii 2 - Search

This is an extract of code from an application I built in Yii 2. It demonstrates a search feature for a dating website.

An overview of the files is as follows:

   - [`controllers/BaseController`](controllers/BaseController.php): This is the base controller that all controllers extend from
   - [`controllers/SearchController`](controllers/SearchController.php): This contains the action that displays the search page
   - [`controllers/ProfileController`](controllers/ProfileController.php): This contains the action that displays a profile page
   - [`models/Helpers`](models/Helpers.php): This contains some helper functions that can be used throughout the application
   - [`models/Profile`](models/Profile.php): This contains the base functions to load and display a user's profile
   - [`models/forms/ProfileSearch`](models/forms/ProfileSearch.php): This contains the functions to perform the search based on query conditions

`SearchController` returns an `ActiveDataProvider` instance that can be used with the `ListView` widget to display the search results.

![screenshot](search.png)
