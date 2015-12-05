angular.module('controllers.bikes', []).controller('BikesCtrl', ['$scope', function ($scope) {
    $scope.bikes = [{
        name: 'Unibike Cruzeo 29x11'
    }];
}]);

angular.module('controllers.users').controller('BikesNewCtrl', ['$scope', function ($scope) {
    $scope.bike = {};
    $scope.submit = function () {

    }
}]);
