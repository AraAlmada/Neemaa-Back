angular.module('starter.controllers', [])

.controller('AppCtrl', function($scope, $state, $ionicSideMenuDelegate, localStorageService) {

  $scope.$on('$ionicView.enter', function(){
    $ionicSideMenuDelegate.canDragContent(false);
  });
  $scope.$on('$ionicView.leave', function(){
    $ionicSideMenuDelegate.canDragContent(false);
  });

  $scope.logout = function () {
    localStorageService.clearAll();
    $ionicSideMenuDelegate.toggleRight(false);
    $state.go('app.login');
  };

  $scope.ifAuth = function () {
    if (localStorageService.get('auth') && localStorageService.get('auth') == true) {
      return false;
    } else {
      return true;
    }
  };

  $scope.ifNeemStyler = function () {
    if (localStorageService.get('auth') && localStorageService.get('auth') == true && localStorageService.get('role') == 'neemstyler' ) {
      return true;
    } else {
      return false;
    }
  };

  $scope.ifUser = function () {
    if (localStorageService.get('auth') && localStorageService.get('auth') == true && localStorageService.get('role') == 'user' ) {
      return true;
    } else {
      return false;
    }
  };

  $scope.ifAdmin = function () {
    if (localStorageService.get('auth') && localStorageService.get('auth') == true && localStorageService.get('role') == 'admin' ) {
      return true;
    } else {
      return false;
    }
  }

})

.controller('AdminCtrl', function($scope, $state, $ionicSideMenuDelegate, localStorageService) {

    console.log('admin');

})

.controller('RegisterCtrl', function ($scope, $state, ionicToast, RegisterService) {
  var ifSociety = false, registerNeemStyler = false;

  $scope.registerUser = function (user) {
    if (!user.firstname) {
      ionicToast.show('Renseignez votre prénom S.V.P', 'top', false, 2500);
    } else if (!user.lastname) {
      ionicToast.show('Renseignez votre nom S.V.P', 'top', false, 2500);
    } else if (!user.telephone || user.telephone.length != 10) {
      ionicToast.show('Votre téléphone semble incorrect', 'top', false, 2500);
    } else if (!/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i.test(user.email)) {
      ionicToast.show('Votre email semble incorrect', 'top', false, 2500);
      delete user.email;
    } else if(!user.password) {
      ionicToast.show('Renseignez votre mot de passe S.V.P', 'top', false, 2500);
    } else if(!user.password) {
      ionicToast.show('Confirmer votre mot de passe S.V.P', 'top', false, 2500);
    } else if(user.password.length <= 5 || user.password.length >= 20) {
      ionicToast.show('Votre mot de passe doit être compris entre 6 et 20 caracthere', 'top', false, 2500);
    } else if (user.password != user.password_confirmation) {
      ionicToast.show('Les mot de passes ne correspondent pas', 'top', false, 2500);
    } else if (ifSociety) {
      if (!user.society) {
        ionicToast.show('Renseignez votre société S.V.P', 'top', false, 2500);
      }
      RegisterService.register(user)
        .success(function () {
          delete user.society;
          $state.go('app.login');
        })
        .error(function (err) {
          ionicToast.show('Une erreur inconnue d\'est produite', 'top', false, 2500);
        });
    } else {
      RegisterService.register(user)
        .success(function () {
          $state.go('app.login');
      })
        .error(function (err) {
          ionicToast.show('Une erreur inconnue d\'est produite', 'top', false, 2500);
        });
    }
  };

  $scope.neemStyler = function () {
    if (ifSociety) {
      ifSociety = false;
    } else {
      ifSociety = true;
    }
  };

  $scope.ifNeemStyler = function () {
    return ifSociety;
  }
})

.controller('LoginCtrl', function ($scope, $state, ionicToast, localStorageService, LoginService) {
  $scope.loginUser = function (user) {
    if (!/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i.test(user.email)) {
      ionicToast.show('L\'email semble incorrect', 'top', false, 2500);
    } else if (user.password.length <= 5 || user.password.length >= 20) {
      ionicToast.show('Votre mot de passe doit être compris entre 6 et 20 caracthere', 'top', false, 2500);
    } else {
      LoginService.login(user)
        .success(function (data) {
          if (data.data === 'user_logged') {
            localStorageService.clearAll();
            localStorageService.set('email', user.email);
            localStorageService.set('token', data.token);
            localStorageService.set('auth', true);
            localStorageService.set('role', 'user');
            $state.go('app.search');
          } else if (data.data === 'admin_logged') {
            localStorageService.clearAll();
            localStorageService.set('email', user.email);
            localStorageService.set('token', data.token);
            localStorageService.set('auth', true);
            localStorageService.set('role', 'admin');
            $state.go('app.admin');
          }  else {
            localStorageService.clearAll();
            localStorageService.set('email', user.email);
            localStorageService.set('token', data.token);
            localStorageService.set('auth', true);
            localStorageService.set('role', 'neemstyler');
            $state.go('app.profilNeemStyler');
          }
        })
        .error(function (err) {
          if (err.error == 'user_not_valide') {
            ionicToast.show('Confirmer votre compte par email, pour renvoyer, cliquer <a ui-sref="app.resend">ici</a>', 'top', false, 2500);
          } else if (err.error == 'neemstyler_not_valide') {
            ionicToast.show('Confirmer votre compte par email, pour renvoyer, cliquer <a ui-sref="app.resend">ici</a>', 'top', false, 2500);
          }  else {
            ionicToast.show('Utilisateurs non reconnue', 'top', false, 2500);
            delete user.email;
            delete user.password;
          }
        });
    }
  };
})

.controller('SearchCtrl', function ($scope) {
  console.log('test');
})

.controller('RecoveryCtrl', function ($scope, $state, ionicToast, RecoveryPasswordService) {
  $scope.recoveryPassword = function (user) {
    if (!user) {
      ionicToast.show('Renseignez votre email S.V.P', 'top', false, 2500);
    } else if (!/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i.test(user.email)) {
      ionicToast.show('Votre Email semble incorrect', 'top', false, 2500);
    } else {
      RecoveryPasswordService.recoveryPassword(user)
        .success(function (data) {

      })
        .error(function (err) {

      });
    }
  }
})

.controller('ProfilUserCtrl', function ($scope, $state, $ionicModal, ProfilUserService, localStorageService, ionicToast) {
  var select = document.getElementById('profil-select-color'), female = false, male = false;
  select.onchange = function () {
    select.className = this.options[this.selectedIndex].className;
  };

  $scope.isCheckedFemale = function () {
    return female;
  };

  $scope.isCheckedMale = function () {
    return male;
  };

  $scope.profilUserCheckboxFemale = function () {
    female = true;
    male = false;
  };

  $scope.profilUserCheckboxMale = function () {
    female = false;
    male = true;
  };

  setTimeout(function () {
    ProfilUserService.getUser(localStorageService.get('email'), localStorageService.get('token'))
      .success(function (data) {
        localStorageService.set('token', data.token);
        localStorageService.set('auth', true);
        var date = data.response[0].birthdate.split(' ');
        data.response[0].birthdate = new Date(date[0]);
        if (data.response[0].sex == 'm') {
          male = true;
        } else if (data.response[0].sex == 'f') {
          female = true;
        }
        $scope.updateProfilUserN = data.response[0];
      })
      .error(function () {
        ionicToast.show('Une erreur est survenue', 'top', false, 4000);
      });
  }, 1000);

  $scope.updateProfilUser = function (user) {
    if (female) {
      user.sex = 'f';
    } else if (male) {
      user.sex = 'm';
    }
    if (!user.firstname) {
      ionicToast.show('Indiquer votre prénom SVP', 'top', false, 4000);
    } else if (!user.lastname) {
      ionicToast.show('Indiquer votre nom SVP', 'top', false, 4000);
    } else if (!user.email) {
      ionicToast.show('Indiquer votre email SVP', 'top', false, 4000);
    } else if (!/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i.test(user.email)) {
      ionicToast.show('Le format de votre email semble incorrect', 'top', false, 4000);
      delete user.email;
    } else if (user.telephone && user.telephone.length != 10) {
      ionicToast.show('Le téléphone semble incorrect', 'top', false, 4000);
      delete user.telephone;
    } else if (user.cp && user.cp.length != 5) {
      ionicToast.show('Le code postal semble incorrect', 'top', false, 4000);
      delete user.cp;
    } else {
      if (user.neemstyler) {
        ProfilUserService.updateUserToNeemstyler(localStorageService.get('email'), localStorageService.get('token'), user)
          .success(function (data) {
            localStorageService.clearAll();
            ionicToast.show('Votre profil à été mis à jour vers neemstyler', 'top', false, 4000);
            $state.go('app.login');
          })
          .error(function () {
            console.log(err);
          });
      } else {
        ProfilUserService.updateUser(localStorageService.get('email'), localStorageService.get('token'), user)
          .success(function (data) {
            localStorageService.set('token', data.token);
            localStorageService.set('auth', true);
            ionicToast.show('Votre profil à été mis à jour', 'top', false, 4000);
          })
          .error(function (err) {
            console.log(err);
          })
      }
    }
  }
})

.controller('ProfilNeemStylerCtrl', function ($scope, $state, ProfilNeemStylerService, ionicToast, localStorageService) {
  var home = 0, saloon = 0;

  $scope.ifCheckedHome = function () {
    return home;
  };

  $scope.ifCheckedSaloon = function () {
    return saloon;
  };

  $scope.checkHome = function () {
    if (home) {
      home = 0;
    } else {
      home = 1;
    }
  };

  $scope.checkSaloon = function () {
    if (saloon) {
      saloon = 0;
    } else {
      saloon = 1;
    }
  };

  setTimeout(function () {
    ProfilNeemStylerService.getNeemStyler(localStorageService.get('email'), localStorageService.get('token'))
      .success(function (data) {
        if (data.response[0].home == 1) {
          home = 1;
        }
        if (data.response[0].saloon == 1) {
          saloon = 1;
        }
        localStorageService.set('token', data.token);
        localStorageService.set('auth', true);
        $scope.updateProfilNeemStyler = data.response[0];
      })
      .error(function (err) {
        console.log(err);
      });
  }, 1000);

  $scope.updateProfilNeemStylerN = function (user) {
    if (user.home == true) {
      user.home1 = home;
    }
    if (user.saloon == true) {
      user.saloon1 = saloon;
    }
    ProfilNeemStylerService.updateNeemStyler(localStorageService.get('email'), localStorageService.get('token'), user)
      .success(function (data) {
        console.log(data);
        localStorageService.set('token', data.token);
        localStorageService.set('auth', true);
        ionicToast.show('Votre profil a bien ete mis a jour', 'top', false, 4000);
        //$state.go('app.profilNeemStyler');
      })
      .error(function (err) {
        console.log(err);
      });
  };
})

.controller('ListCtrl', function ($scope, $state, ionicToast, RecoveryPasswordService) {
  console.log('list neemstyler');
})

.controller('InfoNeemStylerCtrl', function ($scope, $state, ionicToast, RecoveryPasswordService) {
  console.log('list neemstyler');
});
