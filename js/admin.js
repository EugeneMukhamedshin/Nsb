angular.module('adminApp', [])
    .controller('adminController', function ($scope, $http) {
        var admCtrl = this;

        admCtrl.Page = 1;
        admCtrl.IsLastPage = false;
        admCtrl.IsFirstPage = false;
        admCtrl.PageSize = 10;
        admCtrl.SelectedModel = {};

        admCtrl.Refresh = function () {
            admCtrl.models = [];
            $http.get("models.php?page=" + admCtrl.Page + "&pageSize=" + admCtrl.PageSize).then(function (response) {
                console.log(response);
                var models = response.data.models;
                admCtrl.IsFirstPage = admCtrl.Page == 1;
                admCtrl.IsLastPage = models.length != admCtrl.PageSize + 1;
                if (!admCtrl.IsLastPage)
                    models.pop();
                models.forEach(function (model) {
                    var vm = new viewModel(model);
                    admCtrl.models.push(vm);
                });
                if (models.length > 0)
                    admCtrl.SelectModel(admCtrl.models[0]);
            });
        };

        admCtrl.PrevPage = function () {
            if (admCtrl.Page == 1)
                return;
            admCtrl.Page--;
            admCtrl.Refresh(admCtrl.Page);
        };

        admCtrl.NextPage = function () {
            if (admCtrl.models.length < admCtrl.PageSize)
                return;
            admCtrl.Page++;
            admCtrl.Refresh(admCtrl.Page);
        };

        admCtrl.SelectModel = function (model) {
            if (admCtrl.SelectedModel)
                admCtrl.SelectedModel.IsSelected = false;
            admCtrl.SelectedModel = model;
            admCtrl.SelectedModel.IsSelected = true;
        };

        admCtrl.AddModel = function() {
            $http
                .get("add.php")
                .then(function(response) {
                    console.log(response);
                    var vm = new viewModel(response.data.model);
                    admCtrl.models.shift();
                    admCtrl.models.push(vm);
                    admCtrl.SelectModel(vm);
                })
        };

        admCtrl.DeleteSelected = function () {
            var selected = _.filter(admCtrl.models, function (model) {
                return model.IsChecked;
            });
            var config = {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
                }
            };
            $http
                .post("delete.php", selected, config)
                .success(function () {
                    if (selected.length == admCtrl.models.length && admCtrl.Page > 1)
                        admCtrl.Page--;
                    admCtrl.Refresh();
                })
                .error(function () {
                    admCtrl.Refresh();
                });
        };

        function UploadBlob(fileName, blob) {
            console.log('UploadBlob ' + fileName);
        }

        function UploadFile(file) {
            console.log('UploadFile ' + file.name);
        }

        function HandleFile(file) {
            if (file.name.indexOf('.obj') != -1) {
                var zip = JSZip();
                zip.file(file.name, file);
                zip.generateAsync({type:"blob"})
                    .then(function (blob) {
                        UploadBlob(file.name, blob);
                    });
            }
            else {
                UploadFile(file);
            }
        }

        $scope.HandleFiles = function (input) {
            console.log(input.files);

            for (var i = 0; i < input.files.length; i++) {
                HandleFile(input.files[i]);
            }
        };

        admCtrl.Refresh();
    });