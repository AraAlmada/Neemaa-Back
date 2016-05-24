// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
// 'starter.controllers' is found in controllers.js
angular.module('starter', ['ionic', 'ionic-toast', 'starter.controllers', 'starter.services', 'starter.run', 'LocalStorageModule'])

.config(function($stateProvider, $urlRouterProvider) {
  $stateProvider

    .state('app', {
    url: '/app',
    abstract: true,
    templateUrl: 'templates/menu.html',
    controller: 'AppCtrl'
  })

  .state('app.search', {
    url: '/search',
    views: {
      'menuContent': {
        templateUrl: 'templates/search.html',
        controller: 'SearchCtrl'
      }
    },
    requireLogin: true
  })

  .state('app.register', {
    url: '/register',
    views: {
      'menuContent': {
        templateUrl: 'templates/register.html',
        controller: 'RegisterCtrl'
      }
    },
    requireLogin: false
  })

  .state('app.recovery', {
    url: '/recovery',
    views: {
      'menuContent': {
        templateUrl: 'templates/recovery_password.html',
        controller: 'RecoveryCtrl'
      }
    },
    requireLogin: false
  })

  .state('app.login', {
    url: '/login',
    views: {
      'menuContent': {
        templateUrl: 'templates/login.html',
        controller: 'LoginCtrl'
      }
    },
    requireLogin: false
  })
  .state('app.admin', {
    url: '/admin',
    views: {
      'menuContent': {
        templateUrl: 'templates/admin.html',
        controller: 'AdminCtrl'
      }
    },
    requireLogin: true
  })
  .state('app.profil', {
    url: '/profil',
    views: {
      'menuContent': {
        templateUrl: 'templates/profil.html',
        controller: 'ProfilUserCtrl'
      }
    },
    requireLogin: true
  })
  .state('app.list', {
    url: '/list',
    views: {
      'menuContent': {
        templateUrl: 'templates/list-neemstyler.html',
        controller: 'ListCtrl'
      }
    },
    requireLogin: true
  })
  .state('app.neemstyler-info', {
    url: '/neemstyler-info',
    views: {
      'menuContent': {
        templateUrl: 'templates/neemstyler-info.html',
        controller: 'InfoNeemStylerCtrl'
      }
    },
    requireLogin: true
  })
  .state('app.profilNeemStyler', {
    url: '/profil-neemstyler',
    views: {
      'menuContent': {
        templateUrl: 'templates/profil-neemstyler.html',
        controller: 'ProfilNeemStylerCtrl'
      }
    },
    requireLogin: true
  });
  // if none of the above states are matched, use this as the fallback
  $urlRouterProvider.otherwise('/app/search');
});
