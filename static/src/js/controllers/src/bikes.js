angular.module('controllers.bikes', []).controller('BikesCtrl', ['$scope', 'BikesSvc', function ($scope, BikesSvc) {
    $scope.bikes = [];

    $scope.getBikes = function () {
        BikesSvc.getBikes().then(function (bikes) {
            $scope.bikes = bikes;
        });
    };

    $scope.delete = function (id) {
        BikesSvc.deleteBike(id).then(function () {
            $scope.getBikes();
        })
    };

    $scope.getBikes();
}]);

angular.module('controllers.bikes').controller('BikesNewCtrl', ['$scope', '$location', 'BikesSvc', function ($scope, $location, BikesSvc) {
    $scope.bike = {};
    $scope.submit = function () {
        BikesSvc.addBike($scope.bike).then(function (res) {
            if(res.length > 0){
                $location.path('/bikes/show/' + res[0]);
            } else {
                $location.path('/bikes/');
            }
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

angular.module('controllers.bikes').controller('BikesPartsNewCtrl', ['$scope', '$location', 'BikesSvc', 'PartsSvc', function ($scope,  $location, BikesSvc, PartsSvc) {
    $scope.data = {
        bike_ID: $scope.$parent.ID
    };
    $scope.parts = [];

    PartsSvc.getParts().then(function (parts) {
       $scope.parts = parts;

        if ($scope.parts.length > 0) {
            $scope.data.part_ID = $scope.parts[0].ID;
        }
    });

    $scope.submit = function () {
        BikesSvc.addBikePart($scope.data).then(function (res) {
            if(res.length > 0){
                $location.path('/bikes/show/' +  + $scope.data.bike_ID);
            } else {
                $location.path('/bikes/');
            }
        });
    }
}]);
