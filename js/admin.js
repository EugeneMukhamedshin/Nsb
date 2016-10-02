angular.module('adminApp', [])
    .controller('adminController', function ($scope, $http) {
        var adminController = this;

        adminController.Page = 1;
        adminController.PageSize = 5;

        adminController.Refresh = function (page) {
            adminController.models = [];
            $http.get("models.php?page=" + page).then(function (response) {
                console.log(response);
                response.data.models.forEach(function (model) {
                    adminController.models.push(
                        {
                            IsSelected: false,
                            Model: model,
                            Select: function () {
                                adminController.models.forEach(function (model) {
                                    model.IsSelected = false;
                                });
                                this.IsSelected = true;
                            }
                        });
                });
            });
        };

        adminController.PrevPage = function () {
            adminController.Page--;
            adminController.Refresh(adminController.Page);
        };

        adminController.NextPage = function () {
            adminController.Page++;
            adminController.Refresh(adminController.Page);
        };

        adminController.DeleteSelected = function () {
            var selected = _.filter(adminController.models, function (model) {
                return model.IsSelected;
            });
            var config = {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            };
            $http
                .post("delete.php", selected, config)
                .success(function () {
                    adminController.Refresh();
                })
                .error(function () {
                    adminController.Refresh();
                });
        };

        adminController.AddModel = function () {

        };

        adminController.Refresh(adminController.Page);
    });