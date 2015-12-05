angular.module('controllers.nav', []).controller('NavCtrl', ['$scope', function ($scope) {
    $scope.urls = [
        {path: '#/bikes/', name: 'Rowery', active: false},
        {path: '#/routes/', name: 'Trasy', active: false},
        {path: '#/users/', name: 'Tablica', active: false},
        {path: '#/messages/', name: 'Wiadomości', active: false}
    ];
}]);