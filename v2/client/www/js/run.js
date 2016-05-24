angular.module('starter.run', [])

.run(function($ionicPlatform, $rootScope, $state, ionicToast, localStorageService, AuthService) {
  $ionicPlatform.ready(function() {
    // Hide the accessory bar by default (remove this to show the accessory bar above the keyboard
    // for form inputs)
    if (window.cordova && window.cordova.plugins.Keyboard) {
      cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
      cordova.plugins.Keyboard.disableScroll(true);

    }
    if (window.StatusBar) {
      // org.apache.cordova.statusbar required
      StatusBar.styleDefault();
    }
  });

  $rootScope.$on('$stateChangeStart', (event, toState) => {
    var email = localStorageService.get('email'), token = localStorageService.get('token');
    if (toState.requireLogin) {
      if (!AuthService.isAuthenticateLocal()) {
        event.preventDefault();
        ionicToast.show('Vous devez vous authentifier', 'top', false, 2500);
        $state.go('app.login');
      } else {
        if (localStorageService.get('role') == 'user') {
          AuthService.isAuthenticate(email, token)
            .success((data) => {
              console.log(data);
              localStorageService.set('token', data.token);
              localStorageService.set('auth', true);
            })
            .error(() => {
              event.preventDefault();
              ionicToast.show('Vous devez vous authentifier', 'top', false, 2000);
              localStorageService.set('auth', false);
              $state.go('app.login');
            });
        } else if (localStorageService.get('role') == 'neemstyler') {
          AuthService.isAuthenticateNeemStyler(email, token)
            .success((data) => {
              localStorageService.set('token', data.token);
              localStorageService.set('auth', true);
            })
            .error(() => {
              event.preventDefault();
              ionicToast.show('Vous devez vous authentifier', 'top', false, 2000);
              localStorageService.set('auth', false);
              $state.go('app.login');
            });
        } else if (localStorageService.get('role') == 'admin') {
          AuthService.isAuthenticateAdmin(email, token)
            .success((data) => {
              localStorageService.set('token', data.token);
              localStorageService.set('auth', true);
            })
            .error(() => {
              event.preventDefault();
              ionicToast.show('Vous devez être administrateur', 'top', false, 2000);
              localStorageService.set('auth', false);
              $state.go('app.login');
            });
        } else {
          $state.go('app.login');
        }
      }
    }
  });
});
