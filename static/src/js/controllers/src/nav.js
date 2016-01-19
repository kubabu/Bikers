angular.module('controllers.nav', []).controller('NavCtrl', ['$scope', 'AuthSvc', function ($scope, AuthSvc) {
    $scope.unread_msg_count = 0;
    $scope.urls = [
        {path: '#/users/', name: 'Tablica', active: false},
        {path: '#/bikes/', name: 'Rowery', active: false},
        {path: '#/routes/', name: 'Trasy', active: false},
        {path: '#/messages/', name: 'Wiadomości', active: false}
    ];

    $scope.logout = function () {
        AuthSvc.logout(true);
    }
}]);