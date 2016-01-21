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
        });
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

angular.module('controllers.bikes').controller('BikesEditCtrl', ['$scope', '$location', 'BikesSvc', function ($scope, $location, BikesSvc) {
    $scope.bike = {};

    if (angular.isDefined($scope.$parent.ID) && !isNaN($scope.$parent.ID)) {
        BikesSvc.getBikes({id: $scope.$parent.ID}).then(function (bikes) {
            $scope.bike = bikes[0];
        });
    }

    $scope.submit = function () {
        BikesSvc.editBike($scope.bike).then(function (res) {
            if(res.length > 0){
                $location.path('/bikes/show/' + res[0]);
            } else {
                $location.path('/bikes/');
            }
        });
    }
}]);

angular.module('controllers.bikes').controller('BikesShowCtrl', ['$scope', 'BikesSvc', 'UsersSvc', function ($scope, BikesSvc, UsersSvc) {
    $scope.new_comment = {};
    UsersSvc.getUsers({"_me": true}).then(function(resp){
        if(angular.isArray(resp) && resp.length > 0) {
            $scope.cur_user = resp[0];
        }
    });

        function initNewComment (){
        $scope.new_comment = {
            bike_ID: $scope.bike.ID,
        };
    }

    if (angular.isDefined($scope.$parent.ID) && !isNaN($scope.$parent.ID)) {
        BikesSvc.getBikes({id: $scope.$parent.ID, _comments: true, _comments_users: true}).then(function (bikes) {
            $scope.bike = bikes[0];
            initNewComment();

            if(angular.isArray($scope.bike.comments) && $scope.bike.comments.length > 0) {
                $scope.bike.comments = $scope.bike.comments.map(function(comment){
                    comment.date_create =  moment(comment.date_create).valueOf();
                    return comment;
                })
            }
        });
    }

    $scope.addComment = function(){

        BikesSvc.addBikeComment($scope.new_comment).then(function (){
            $scope.new_comment.date_create = new Date();
            $scope.new_comment.first_name = $scope.cur_user.first_name;
            $scope.new_comment.last_name = $scope.cur_user.last_name + " (Ty)";

            $scope.bike._comments.unshift(angular.copy($scope.new_comment));
            initNewComment();
        })
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
