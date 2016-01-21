angular.module('controllers.nav', []).controller('NavCtrl', ['$scope', 'AuthSvc', '$interval', '$rootScope', function ($scope, AuthSvc, $interval, $rootScope) {
    $scope.unread_msg_count = 0;
    $scope.urls = [
        {path: '#/users/', name: 'Tablica', active: false},
        {path: '#/bikes/', name: 'Rowery', active: false},
        {path: '#/routes/', name: 'Trasy', active: false},
        {path: '#/messages/', name: 'Wiadomości', active: false}
    ];

    function random() {
        return Math.floor(Math.random() * 8 + 1);
    }

    $rootScope.background = random();

    //$interval(function () {
    //    $rootScope.background = random();
    //}, 10000);

    $scope.logout = function () {
        AuthSvc.logout(true);
    }
}]);