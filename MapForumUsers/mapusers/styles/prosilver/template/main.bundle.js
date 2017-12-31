webpackJsonp(["main"],{

/***/ "../../../../../src/$$_lazy_route_resource lazy recursive":
/***/ (function(module, exports) {

function webpackEmptyAsyncContext(req) {
	// Here Promise.resolve().then() is used instead of new Promise() to prevent
	// uncatched exception popping up in devtools
	return Promise.resolve().then(function() {
		throw new Error("Cannot find module '" + req + "'.");
	});
}
webpackEmptyAsyncContext.keys = function() { return []; };
webpackEmptyAsyncContext.resolve = webpackEmptyAsyncContext;
module.exports = webpackEmptyAsyncContext;
webpackEmptyAsyncContext.id = "../../../../../src/$$_lazy_route_resource lazy recursive";

/***/ }),

/***/ "../../../../../src/app/app.component.css":
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__("../../../../css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "ngui-map {\r\n\theight: 800px;\r\n}", ""]);

// exports


/*** EXPORTS FROM exports-loader ***/
module.exports = module.exports.toString();

/***/ }),

/***/ "../../../../../src/app/app.component.ts":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return AppComponent; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__("../../../core/esm5/core.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_rxjs_add_observable_from__ = __webpack_require__("../../../../rxjs/_esm5/add/observable/from.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__angular_common_http__ = __webpack_require__("../../../common/esm5/http.js");
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};



var AppComponent = (function () {
    function AppComponent(http, __zone) {
        this.http = http;
        this.__zone = __zone;
        this.mapCenter = [];
        this.selectOptions = [];
        this.selectedLocation = 0;
        this.searchRadius = 50;
        this.loadCrashers = '../assets/crashers.json';
        this.loadGetUser = '/app.php/mapusers/xhr/getUser';
        this.loadRemote = '/app.php/mapusers/xhr/searchUsers';
        this.email = 'myersjj@gmail.com';
        this.password = 'jjm2580J';
        this.positions = [];
        this.items = [];
        this.title = 'Liberation Unleashed Members';
        this.lat = 20.0;
        this.lng = -20.0;
        this.info = {
            id: 0,
            display: true,
            name: null,
            forum_name: null,
            color: null,
            geo: null,
            location: null,
            iconUrl: null,
            label: null,
        };
        this.getUser();
        this.reload(null);
    }
    AppComponent.prototype.log = function (event, str) {
        if (event instanceof MouseEvent) {
            return false;
        }
        console.log('event .... >', event, str);
    };
    AppComponent.prototype.onMapReady = function (map) {
        console.log('map', map);
        console.log('markers', map.markers); // to get all markers as an array
    };
    AppComponent.prototype.onIdle = function (event) {
        console.log('map', event.target);
    };
    AppComponent.prototype.onMarkerInit = function (marker) {
        console.log('marker', marker);
    };
    AppComponent.prototype.onMapClick = function (event) {
        this.positions.push(event.latLng);
        event.target.panTo(event.latLng);
    };
    AppComponent.prototype.showLocation = function (event) {
        var _this = this;
        // console.log('showLocation id=', this.selectedLocation);
        if (this.selectedLocation === 0) {
            return;
        }
        var thisLoc = this.users.find(function (k) { return Number(k.id) === Number(_this.selectedLocation); });
        console.log('thisLoc(', this.selectedLocation, ')=', thisLoc);
        if (thisLoc.geo.latitude) {
            this.mapCenter = [Number(thisLoc.geo.latitude), Number(thisLoc.geo.longitude)];
        }
        else {
            this.mapCenter = [this.lat, this.lng];
        }
        console.log('mapCenter=', this.mapCenter);
    };
    AppComponent.prototype.getUser = function () {
        var _this = this;
        var headers = new __WEBPACK_IMPORTED_MODULE_2__angular_common_http__["c" /* HttpHeaders */]()
            .set('X-Requested-With', 'XMLHttpRequest')
            .set('responseType', 'json');
        // console.log('added headers=', headers);
        var geocoder = new google.maps.Geocoder();
        this.http.get(this.loadGetUser, { headers: headers })
            .subscribe(function (data) {
            console.log('getUser data=', data);
            _this.info = data[0];
            // console.log('home info=', this.info);
            _this.searchLocation = _this.info.location;
            _this.getIconUrl(_this.info);
            _this.positions.push({ latlng: [Number(_this.info.geo.latitude), Number(_this.info.geo.longitude)], item: _this.info });
            console.log('initial position=', _this.positions);
            if (_this.info.geo.latitude) {
                _this.mapCenter = [Number(_this.info.geo.latitude), Number(_this.info.geo.longitude)];
            }
            else {
                _this.mapCenter = [_this.lat, _this.lng];
            }
            console.log('mapCenter=', _this.mapCenter);
        });
    };
    AppComponent.prototype.reload = function (center) {
        var _this = this;
        console.log('reloading from Remote..., center=', center);
        this.clearLocations();
        var items;
        var headers = new __WEBPACK_IMPORTED_MODULE_2__angular_common_http__["c" /* HttpHeaders */]()
            .set('X-Requested-With', 'XMLHttpRequest')
            .set('responseType', 'json');
        // console.log('added headers=', headers);
        var geocoder = new google.maps.Geocoder();
        var params = null;
        if (center) {
            params = new __WEBPACK_IMPORTED_MODULE_2__angular_common_http__["d" /* HttpParams */]().set('lat', center.geometry.location.lat())
                .set('lng', center.geometry.location.lng())
                .set('radius', String(this.searchRadius));
            console.log('reload params=', params);
        }
        this.http.get(this.loadRemote, { params: params, headers: headers })
            .subscribe(function (data) {
            console.log('remote data=', data);
            items = data;
            _this.users = items;
            var _loop_1 = function (item) {
                // console.log('insert new ', item);
                _this.getIconUrl(item);
                _this.info = { id: item.id,
                    geo: { latitude: Number(item.geo.latitude),
                        longitude: Number(item.geo.longitude) },
                    display: true,
                    name: item.name,
                    color: item.color,
                    forum_name: item.forum,
                    location: item.location,
                    iconUrl: item.iconUrl,
                    label: null,
                };
                if (!item.geo.latitude) {
                    var _positions_1 = _this.positions;
                    geocoder.geocode({ address: item.location }, function (results, status) {
                        if (status === google.maps.GeocoderStatus.OK) {
                            console.log('loc=', item.location, ' status=', status, ' results=', results);
                            item.geo.latitude = Number(results[0].geometry.location.lat());
                            item.geo.longitude = Number(results[0].geometry.location.lng());
                            _positions_1.push({ latlng: [Number(item.geo.latitude), Number(item.geo.longitude)], item: item });
                        }
                        else {
                            alert(this.location + ' not found');
                        }
                    });
                }
                else {
                    _this.positions.push({ latlng: [Number(item.geo.latitude), Number(item.geo.longitude)], item: item });
                }
                _this.selectOptions.push([item.id, item.forum, item.iconUrl]);
                // this.updateItem(item, true);
            };
            // console.log('load items=', items);
            for (var _i = 0, items_1 = items; _i < items_1.length; _i++) {
                var item = items_1[_i];
                _loop_1(item);
            }
            // console.log('selectOptions=', this.selectOptions);
        }, function (error) {
            console.log('loadRemote error=', error);
        });
    };
    AppComponent.prototype.reloadLocal = function () {
        var _this = this;
        console.log('reloading from Local...');
        this.clearLocations();
        var items;
        var geocoder = new google.maps.Geocoder();
        this.http.get(this.loadCrashers, { responseType: 'json' })
            .subscribe(function (data) {
            items = data;
            _this.users = items;
            console.log('load items=', items);
            var _loop_2 = function (item) {
                // console.log('insert new ', item);
                _this.getIconUrl(item);
                _this.info = { id: item.id,
                    geo: { latitude: Number(item.geo.latitude),
                        longitude: Number(item.geo.longitude) },
                    display: true,
                    name: item.name,
                    color: item.color,
                    forum_name: item.forum,
                    location: item.location,
                    iconUrl: item.iconUrl,
                    label: null,
                };
                if (!item.latitude) {
                    var _positions_2 = _this.positions;
                    geocoder.geocode({ address: item.location }, function (results, status) {
                        if (status === google.maps.GeocoderStatus.OK) {
                            console.log('loc=', item.location, ' status=', status, ' results=', results);
                            item.geo.latitude = Number(results[0].geometry.location.lat());
                            item.geo.longitude = Number(results[0].geometry.location.lng());
                            _positions_2.push({ latlng: [Number(item.geo.latitude), Number(item.geo.longitude)], item: item });
                        }
                        else {
                            alert(this.location + ' not found');
                        }
                    });
                }
                else {
                    _this.positions.push({ latlng: [Number(item.latitude), Number(item.longitude)], item: item });
                }
                _this.selectOptions.push([item.id, item.forum, item.iconUrl]);
                // this.updateItem(item, true);
            };
            for (var _i = 0, _a = items.crashers; _i < _a.length; _i++) {
                var item = _a[_i];
                _loop_2(item);
            }
            // console.log('selectOptions=', this.selectOptions);
        });
    };
    AppComponent.prototype.markerClick = function (marker) {
        // once marker is given, iterate through your list of markers and
        // change this marker's icon and make sure the rest of the icons are back to their default
        console.log('click marker ', marker);
    };
    AppComponent.prototype.onSliderChange = function (event) {
        console.log(event);
        console.log('radius=', this.searchRadius);
    };
    AppComponent.prototype.clicked = function (event, marker) {
        console.log('clicked marker ', event, marker);
        this.info.geo = { latitude: event.target.getPosition().lat(),
            longitude: event.target.getPosition().lng() };
        this.info.display = true;
        this.info.name = marker.item.name;
        this.info.forum_name = marker.item.forum;
        // console.log('info=', this.info, ' at id=', 'iw-' + marker.item.id);
        this.mapCenter = [Number(this.info.geo.latitude), Number(this.info.geo.longitude)];
        event.target.nguiMapComponent.openInfoWindow('iw-' + marker.item.id, event.target);
    };
    AppComponent.prototype.getIconUrl = function (item) {
        item.label = {
            fontFamily: 'Fontawesome',
            text: '\uf041',
            'font-size': '48px',
            color: '#' + item.color
        };
        item.icon = {
            path: google.maps.SymbolPath.CIRCLE,
            scale: 0
        };
        // console.log('item.label=', item.label);
    };
    AppComponent.prototype.clearLocations = function () {
        console.log('clearLocations()');
        this.positions = [];
        this.info = null;
        this.selectOptions = [];
        this.selectOptions.push([0, 'none', '']);
    };
    AppComponent.prototype.searchLocations = function () {
        var address = this.searchLocation;
        var geocoder = new google.maps.Geocoder();
        var _this = this;
        geocoder.geocode({ address: address }, function (results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                console.log('loc=', address, ' status=', status, ' results=', results);
                _this.searchNearLocations.call(_this, results[0], status);
            }
            else {
                alert(address + ' not found');
            }
        });
    };
    AppComponent.prototype.searchNearLocations = function (center, status) {
        console.log('center=', center);
        this.mapCenter = [Number(center.geometry.location.lat()),
            Number(center.geometry.location.lng())];
        this.reload(center);
    };
    AppComponent = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["n" /* Component */])({
            selector: 'app-gm',
            styles: [__webpack_require__("../../../../../src/app/app.component.css")],
            template: "\n          <h1>Forum User Locations</h1>\n          <div>\n            <mat-form-field>\n                <input matInput [(ngModel)]=\"searchLocation\" placeholder=\"Search location\">\n            </mat-form-field>\n             Radius(km)={{ searchRadius }}\n            <mat-slider [(ngModel)]=\"searchRadius\" (input)=\"this.onSliderChange($event)\"\n                min=\"100\" max=\"20000\" step=\"100\" value=\"200\">\n            </mat-slider>\n            <md-slider></md-slider>\n            <button mat-button (click)=\"searchLocations(this.searchNearLocations)\" [disabled]=\"!searchLocation || !searchRadius\">\n                Search\n            </button>\n            <div>\n                <mat-form-field *ngIf=\"selectOptions\">\n                    <mat-select placeholder=\"Pick a user\" name=\"selectUser\"\n                        [(ngModel)]=\"selectedLocation\" (selectionChange)=\"showLocation()\">\n                        <mat-option *ngFor=\"let opt of selectOptions\" [value]=\"opt[0]\">\n                            {{ opt[1] }}\n                        </mat-option>\n                    </mat-select>\n                </mat-form-field>\n            </div>\n          <ngui-map center=\"{{ mapCenter }}\"\n            [zoom]=\"3\"\n            [zoomControlOptions]=\"{position: 'TOP_CENTER'}\"\n            [fullscreenControl]=\"true\"\n            [fullscreenControlOptions]=\"{position: 'TOP_CENTER'}\"\n            (click)=\"log($event)\"\n            [scrollwheel]=\"false\">\n            <marker *ngFor=\"let pos of positions\" [position]=\"pos.latlng\"\n                    [icon]=\"pos.item.icon\" [label]=\"pos.item.label\"\n                     (click)=\"clicked($event, pos)\">\n                <info-window id=\"iw-{{ pos.item.id }}\">\n                    <div *ngIf=\"info.display\">\n                        {{ info.forum_name }} @ lat: {{ info.geo.latitude }}, lng: {{ info.geo.longitude }}\n                    </div>\n                </info-window>\n            </marker>\n          </ngui-map>\n\n          "
        }),
        __metadata("design:paramtypes", [__WEBPACK_IMPORTED_MODULE_2__angular_common_http__["a" /* HttpClient */],
            __WEBPACK_IMPORTED_MODULE_0__angular_core__["M" /* NgZone */]])
    ], AppComponent);
    return AppComponent;
}());



/***/ }),

/***/ "../../../../../src/app/app.module.ts":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return AppModule; });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_platform_browser__ = __webpack_require__("../../../platform-browser/esm5/platform-browser.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__angular_core__ = __webpack_require__("../../../core/esm5/core.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__angular_forms__ = __webpack_require__("../../../forms/esm5/forms.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__angular_platform_browser_animations__ = __webpack_require__("../../../platform-browser/esm5/animations.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__angular_material_form_field__ = __webpack_require__("../../../material/esm5/form-field.es5.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_5__angular_material_input__ = __webpack_require__("../../../material/esm5/input.es5.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_6__angular_material__ = __webpack_require__("../../../material/esm5/material.es5.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_7__angular_material_button__ = __webpack_require__("../../../material/esm5/button.es5.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_8__angular_material_slider__ = __webpack_require__("../../../material/esm5/slider.es5.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_9__app_component__ = __webpack_require__("../../../../../src/app/app.component.ts");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_10__ngui_map__ = __webpack_require__("../../../../@ngui/map/dist/@ngui/map.es5.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_11__angular_common_http__ = __webpack_require__("../../../common/esm5/http.js");
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};












var AppModule = (function () {
    function AppModule() {
    }
    AppModule = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_1__angular_core__["I" /* NgModule */])({
            imports: [
                __WEBPACK_IMPORTED_MODULE_0__angular_platform_browser__["a" /* BrowserModule */],
                __WEBPACK_IMPORTED_MODULE_2__angular_forms__["c" /* FormsModule */],
                __WEBPACK_IMPORTED_MODULE_4__angular_material_form_field__["c" /* MatFormFieldModule */],
                __WEBPACK_IMPORTED_MODULE_5__angular_material_input__["b" /* MatInputModule */],
                __WEBPACK_IMPORTED_MODULE_3__angular_platform_browser_animations__["a" /* BrowserAnimationsModule */],
                __WEBPACK_IMPORTED_MODULE_6__angular_material__["a" /* MatSelectModule */],
                __WEBPACK_IMPORTED_MODULE_7__angular_material_button__["a" /* MatButtonModule */],
                __WEBPACK_IMPORTED_MODULE_8__angular_material_slider__["a" /* MatSliderModule */],
                __WEBPACK_IMPORTED_MODULE_11__angular_common_http__["b" /* HttpClientModule */],
                __WEBPACK_IMPORTED_MODULE_10__ngui_map__["a" /* NguiMapModule */].forRoot()
            ],
            exports: [
                __WEBPACK_IMPORTED_MODULE_4__angular_material_form_field__["c" /* MatFormFieldModule */],
                __WEBPACK_IMPORTED_MODULE_5__angular_material_input__["b" /* MatInputModule */],
                __WEBPACK_IMPORTED_MODULE_6__angular_material__["a" /* MatSelectModule */],
                __WEBPACK_IMPORTED_MODULE_7__angular_material_button__["a" /* MatButtonModule */],
                __WEBPACK_IMPORTED_MODULE_8__angular_material_slider__["a" /* MatSliderModule */]
            ],
            declarations: [__WEBPACK_IMPORTED_MODULE_9__app_component__["a" /* AppComponent */]],
            providers: [
                __WEBPACK_IMPORTED_MODULE_10__ngui_map__["a" /* NguiMapModule */]
            ],
            schemas: [__WEBPACK_IMPORTED_MODULE_1__angular_core__["i" /* CUSTOM_ELEMENTS_SCHEMA */]],
            bootstrap: [__WEBPACK_IMPORTED_MODULE_9__app_component__["a" /* AppComponent */]]
        })
    ], AppModule);
    return AppModule;
}());



/***/ }),

/***/ "../../../../../src/environments/environment.ts":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "a", function() { return environment; });
// The file contents for the current environment will overwrite these during build.
// The build system defaults to the dev environment which uses `environment.ts`, but if you do
// `ng build --env=prod` then `environment.prod.ts` will be used instead.
// The list of which env maps to which file can be found in `.angular-cli.json`.
var environment = {
    production: false
};


/***/ }),

/***/ "../../../../../src/main.ts":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__angular_core__ = __webpack_require__("../../../core/esm5/core.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__angular_platform_browser_dynamic__ = __webpack_require__("../../../platform-browser-dynamic/esm5/platform-browser-dynamic.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_hammerjs__ = __webpack_require__("../../../../hammerjs/hammer.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_hammerjs___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2_hammerjs__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__app_app_module__ = __webpack_require__("../../../../../src/app/app.module.ts");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__environments_environment__ = __webpack_require__("../../../../../src/environments/environment.ts");





if (__WEBPACK_IMPORTED_MODULE_4__environments_environment__["a" /* environment */].production) {
    Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["_13" /* enableProdMode */])();
}
Object(__WEBPACK_IMPORTED_MODULE_1__angular_platform_browser_dynamic__["a" /* platformBrowserDynamic */])().bootstrapModule(__WEBPACK_IMPORTED_MODULE_3__app_app_module__["a" /* AppModule */])
    .catch(function (err) { return console.log(err); });


/***/ }),

/***/ 0:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__("../../../../../src/main.ts");


/***/ })

},[0]);
//# sourceMappingURL=main.bundle.js.map