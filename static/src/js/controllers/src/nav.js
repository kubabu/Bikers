angular.module('controllers.nav', []).controller('NavCtrl', ['$scope', function ($scope) {
    $scope.urls = [
        {path: '#/', name: 'Strona główna', active: true},
        {path: '#/bikes/', name: 'Rowery', active: false}
    ];
}]);