angular.module('controllers.login', []).controller('LoginCtrl', ['$scope', 'AuthSvc', '$timeout', function ($scope, AuthSvc, $timeout) {
    $scope.username = '';
    $scope.password = '';
    $scope.alert = {
        type: 'success',
        msg: ''
    };

    $scope.submit = function () {
        AuthSvc.login().then(function (status) {
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
}]);