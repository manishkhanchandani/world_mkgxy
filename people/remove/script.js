// script.js

	// create the module and name it myApp
        // also include ngRoute for all our routing needs
	var myApp = angular.module('myApp', []);

  // configure our routes
  myApp.config(function($routeProvider) {
    $routeProvider
  
      // route for the home page
      .when('/people', {
        templateUrl : 'pages/home.html',
        controller  : 'mainController'
      })
  
      // route for the about page
      .when('/people/about', {
        templateUrl : 'pages/about.html',
        controller  : 'aboutController'
      })
  
      // route for the contact page
      .when('/people/contact', {
        templateUrl : 'pages/contact.html',
        controller  : 'contactController'
      });
  });
  // create the controller and inject Angular's $scope
  myApp.controller('mainController', function($scope) {
  
    // create a message to display in our view
    $scope.message = 'Everyone come and see how good I look!';
  });
  
  myApp.controller('aboutController', function($scope) {
    $scope.message = 'Look! I am an about page.';
  });

  myApp.controller('contactController', function($scope) {
    $scope.message = 'Contact us! JK. This is just a demo.';
  });