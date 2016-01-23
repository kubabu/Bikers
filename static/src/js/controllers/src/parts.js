angular.module('controllers.parts', []).controller('PartsEditCtrl', ['$scope', '$location', 'PartsSvc', function ($scope, $location, PartsSvc) {
    $scope.part = {};

    if (angular.isDefined($scope.$parent.ID) && !isNaN($scope.$parent.ID)) {
        PartsSvc.getParts({id: $scope.$parent.ID}).then(function (parts) {
            $scope.part = parts[0];
        });
    }

    $scope.submit = function () {
        PartsSvc.editPart($scope.part).then(function (res) {
            if(res.length > 0){
                $location.path('/bikes/show/' + $scope.part._bike_ID);
            } else {
                $location.path('/bikes/');
            }
        });
    };
}]);

angular.module('controllers.parts').controller('PartsNewCtrl', ['$scope', '$location', 'PartsSvc', function ($scope, $location, PartsSvc) {
    $scope.part = {
        _bike_ID: $scope.$parent.ID
    };

    $scope.submit = function () {
        PartsSvc.addPart($scope.part).then(function (res) {
            if(res.length > 0){
                if(angular.isDefined($scope.part._bike_ID) && typeof $scope.part._bike_ID != 'undefined') {
                    $location.path('/bikes/show/' + $scope.part._bike_ID);
                } else {
                    $location.path('/parts/');
                }
            }
        });
    };
}]);

angular.module('controllers.parts').controller('PartsCtrl', ['$scope', 'PartsSvc', function ($scope, PartsSvc) {
    $scope.parts = [];

    getParts = function(){
        PartsSvc.getParts().then(function (parts) {
            $scope.parts = parts;
        });
    };

    getParts();

    $scope.delete = function (id) {
        PartsSvc.deletePart(id).then(function () {
            getParts();
        });
    };
}]);
