angular.module('starter.services', [])

.factory('RegisterService', function($http){
  return {
    register: function (user) {
      return $http({
        method: 'POST',
        url: 'http://localhost:8000/api/register',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        transformRequest: function(obj) {
          var str = [];
          for(var p in obj)
            str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
          return str.join("&");
        },
        data: user
      });
    }
  }
})

.factory('LoginService', function($http){
  return {
    login: function (user) {
      return $http({
        method: 'POST',
        url: 'http://localhost:8000/api/login',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        transformRequest: function(obj) {
          var str = [];
          for(var p in obj)
            str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
          return str.join("&");
        },
        data: user
      });
    }
  }
})

.factory('AuthService', function ($http, localStorageService) {
  return {
    isAuthenticateLocal: function () {
        if (!localStorageService.get('email') || !localStorageService.get('token')) {
          return false;
        } else if (!/^[_a-z0-9]+(\.[_a-z0-9]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/.test(localStorageService.get('email'))) {
          return false;
        } else if (localStorageService.get('token').length != 60) {
          return false;
        } else {
          return true;
        }
    },

    isAuthenticate: function (email, token) {
        return $http({
          method: 'GET',
          url: 'http://localhost:8000/api/auth',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'Authorization': `${email}token:${token}`
          },
          transformRequest: (obj) => {
            let str = [];
            for(let p in obj)
              str.push(encodeURIComponent(p) + '=' + encodeURIComponent(obj[p]));
            return str.join('&');
          }
        });
    },

    isAuthenticateNeemStyler: function (email, token) {
      return $http({
        method: 'GET',
        url: 'http://localhost:8000/api/auth/neemstyler',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'Authorization': `${email}token:${token}`
        },
        transformRequest: (obj) => {
          let str = [];
          for(let p in obj)
            str.push(encodeURIComponent(p) + '=' + encodeURIComponent(obj[p]));
          return str.join('&');
        }
      });
    },

    isAuthenticateAdmin: function (email, token) {
      return $http({
        method: 'GET',
        url: 'http://localhost:8000/api/auth/admin',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'Authorization': `${email}token:${token}`
        },
        transformRequest: (obj) => {
          let str = [];
          for(let p in obj)
            str.push(encodeURIComponent(p) + '=' + encodeURIComponent(obj[p]));
          return str.join('&');
        }
      });
    }
  }
})

.factory('RecoveryPasswordService', function($http){
  return {
    recoveryPassword: function (user) {
      return $http({
        method: 'POST',
        url: 'http://localhost:8000/api/recovery',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        transformRequest: function(obj) {
          var str = [];
          for(var p in obj)
            str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
          return str.join("&");
        },
        data: user
      });
    }
  }
})

.factory('ProfilUserService', function($http){
  return {
    getUser: function (email, token) {
      return $http({
        method: 'POST',
        url: 'http://localhost:8000/api/get-user',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'Authorization': `${email}token:${token}`
        },
        transformRequest: (obj) => {
          let str = [];
          for(let p in obj)
            str.push(encodeURIComponent(p) + '=' + encodeURIComponent(obj[p]));
          return str.join('&');
        },
        data: email
      });
    },

    updateUser: function (email, token, user) {
      return $http({
        method: 'PUT',
        url: 'http://localhost:8000/api/update-user',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'Authorization': `${email}token:${token}`
        },
        transformRequest: (obj) => {
          let str = [];
          for(let p in obj)
            str.push(encodeURIComponent(p) + '=' + encodeURIComponent(obj[p]));
          return str.join('&');
        },
        data: user
      });
    },

    updateUserToNeemstyler: function (email, token, user) {
      return $http({
        method: 'POST',
        url: 'http://localhost:8000/api/update-user-to-neemstyler',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'Authorization': `${email}token:${token}`
        },
        transformRequest: (obj) => {
          let str = [];
          for(let p in obj)
            str.push(encodeURIComponent(p) + '=' + encodeURIComponent(obj[p]));
          return str.join('&');
        },
        data: user
      });
    }
  }
})

.factory('ProfilNeemStylerService', function ($http) {
  return {
    getNeemStyler: function (email, token) {
      return $http({
        method: 'GET',
        url: 'http://localhost:8000/api/get-neemstyler',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'Authorization': `${email}token:${token}`
        },
        transformRequest: (obj) => {
          let str = [];
          for(let p in obj)
            str.push(encodeURIComponent(p) + '=' + encodeURIComponent(obj[p]));
          return str.join('&');
        }
      });
    },

    updateNeemStyler: function (email, token, user) {
      return $http({
        method: 'POST',
        url: 'http://localhost:8000/api/update-neemstyler',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'Authorization': `${email}token:${token}`
        },
        transformRequest: (obj) => {
          let str = [];
          for(let p in obj)
            str.push(encodeURIComponent(p) + '=' + encodeURIComponent(obj[p]));
          return str.join('&');
        },
        data: user
      });
    }
  }
})

.factory('InfoNeemStylerService', function($http){
  return {
    getUser: function (email) {
      return $http({
        method: 'POST',
        url: 'http://localhost:8000/api/getUser',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        transformRequest: function(obj) {
          var str = [];
          for(var p in obj)
            str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
          return str.join("&");
        },
        data: user
      });
    }
  }
})

.factory('SearchService', function($http){
    return {
      getNeemStylers: function (email, token, search) {
        return $http({
          method: 'POST',
          url: 'http://localhost:8000/api/search',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'Authorization': `${email}token:${token}`
          },
          transformRequest: function(obj) {
            var str = [];
            for(var p in obj)
              str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
            return str.join("&");
          },
          data: search
        });
      },

      getNeemstyler: function (email, token, society) {
        return $http({
          method: 'POST',
          url: 'http://localhost:8000/api/get-neemstyler-search',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'Authorization': `${email}token:${token}`
          },
          transformRequest: function(obj) {
            var str = [];
            for(var p in obj)
              str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
            return str.join("&");
          },
          data: society
        });
      }
    }
  });

