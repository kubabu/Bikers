angular.module('controllers.nav', []).controller('NavCtrl', ['$scope', function ($scope) {
    $scope.urls = [
        {path: '#/users/', name: 'Tablica', active: false},
        {path: '#/bikes/', name: 'Rowery', active: false},
        {path: '#/routes/', name: 'Trasy', active: false},
        {path: '#/messages/', name: 'Wiadomości', active: false}
    ];
}]);