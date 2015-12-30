angular.module('controllers.bikes', []).controller('BikesCtrl', ['$scope', 'BikesSvc', function ($scope, BikesSvc) {
    $scope.bikes = [];

    BikesSvc.getBikes().then(function (bikes) {
        $scope.bikes = bikes;
    });
}]);

angular.module('controllers.bikes').controller('BikesNewCtrl', ['$scope', 'BikesSvc', function ($scope, BikesSvc) {
    $scope.bike = {};
    $scope.submit = function () {
        BikesSvc.addBike($scope.bike).then(function () {

        });
    }
}]);

angular.module('controllers.bikes').controller('BikesShowCtrl', ['$scope', 'BikesSvc', function ($scope, BikesSvc) {
    $scope.bike = {};

    if (angular.isDefined($scope.$parent.ID) && !isNaN($scope.$parent.ID)) {
        BikesSvc.getBikes({id: $scope.$parent.ID}).then(function (bikes) {
            $scope.bike = bikes[0];
        });
    }
}]);
