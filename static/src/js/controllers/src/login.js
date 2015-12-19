angular.module('controllers.login', []).controller('LoginCtrl', ['$scope', 'AuthSvc', '$timeout', function ($scope, AuthSvc, $timeout) {
    console.log('tutaj');

    $scope.username = '';
    $scope.password = '';
    $scope.alert = {
        type: 'success',
        msg: ''
    };

    $scope.login = function () {
        AuthSvc.login($scope.username, $scope.password).then(function (status) {
            if (status) {
                $scope.alert = {
                    type: 'success',
                    msg: 'Zalogowano poprawnie. Nastąpi przekierowanie'
                };

                $timeout(function () {
                    window.history.back();
                }, 500);
            } else {
                $scope.alert = {
                    type: 'danger',
                    msg: 'Niepoprawny login lub hasło'
                };
            }
        });
    };

    $scope.register = function () {
        AuthSvc.login($scope.username, $scope.password, true).then(function (status) {
            if (status) {
                $scope.alert = {
                    type: 'success',
                    msg: 'Zarejestrowano poprawnie. Nastąpi przekierowanie'
                };

                $timeout(function () {
                    window.history.back();
                }, 500);
            } else {
                $scope.alert = {
                    type: 'danger',
                    msg: 'Nastąpił nieoczekiwany błąd podczas rejestracji'
                };
            }
        });
    }
}]);
