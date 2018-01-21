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
        this.lat = 20.0;
        this.lng = -20.0;
        this.mapCenter = { lat: this.lat, lng: this.lng };
        this.foundUser = false;
        this.selectOptions = [];
        this.selectedLocation = 0;
        this.searchRadius = 50;
        this.searchLimit = 20;
        this.loadSearchUser = '/app.php/mapusers/xhr/searchUser';
        this.loadSearchLocation = '/app.php/mapusers/xhr/searchLocation';
        this.positions = [];
        this.items = [];
        this.info = {
            id: 0,
            display: false,
            forum_name: null,
            color: null,
            geo: null,
            location: null,
            iconUrl: null,
            label: null,
        };
        this.infoWindow = {
            id: 0,
            display: false,
            forum_name: null,
            color: null,
            geo: null,
            location: null,
            iconUrl: null,
            profileUrl: null,
            label: null,
        };
        this.doSearchUser();
    }
    AppComponent.prototype.onInit = function (map) {
        this.map = map;
        this.fitBounds(this.map);
    };
    AppComponent.prototype.fitBounds = function (map) {
        if (!map) {
            return;
        }
        // console.log('fitBounds map=', map);
        if (map.markers) {
            var bounds_1 = new google.maps.LatLngBounds();
            // console.log('map.markers=', map.markers);
            map.markers.forEach(function (marker) {
                /// console.log('set bounds for marker=', marker);
                bounds_1.extend(marker.position);
                // console.log('Extend bounds=', bounds);
            });
            // console.log('Fit map to bounds=', bounds);
            map.fitBounds(bounds_1);
        }
    };
    AppComponent.prototype.log = function (event, str) {
        if (event instanceof MouseEvent) {
            return false;
        }
        console.log('event .... >', event, str);
    };
    AppComponent.prototype.onIdle = function (event) {
        // console.log('map idle ', event.target);
        this.map = event.target;
        this.fitBounds(this.map);
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
            this.mapCenter = { lat: Number(thisLoc.geo.latitude), lng: Number(thisLoc.geo.longitude) };
        }
        else {
            this.mapCenter = { lat: this.lat, lng: this.lng };
        }
        console.log('mapCenter=', this.mapCenter);
    };
    AppComponent.prototype.doSearchUser = function () {
        var _this = this;
        this.searchErrorMessage = null;
        var headers = new __WEBPACK_IMPORTED_MODULE_2__angular_common_http__["c" /* HttpHeaders */]()
            .set('X-Requested-With', 'XMLHttpRequest')
            .set('responseType', 'json');
        // console.log('added headers=', headers);
        var params = null;
        if (this.searchUser) {
            params = new __WEBPACK_IMPORTED_MODULE_2__angular_common_http__["d" /* HttpParams */]().set('name', this.searchUser)
                .set('radius', String(this.searchRadius))
                .set('limit', String(this.searchLimit));
            console.log('doSearchUser params=', params);
        }
        this.http.get(this.loadSearchUser, { params: params, headers: headers })
            .subscribe(function (data) {
            console.log('getUser data=', data);
            _this.info = data[0];
            // console.log('home info=', this.info);
            _this.searchLocation = _this.info.location;
            _this.getIconUrl(_this.info);
            // this.positions.push({latlng: [Number(this.info.geo.latitude), Number(this.info.geo.longitude)], item: this.info});
            console.log('initial position=', _this.positions);
            if (_this.info.geo.latitude) {
                _this.mapCenter = { lat: Number(_this.info.geo.latitude), lng: Number(_this.info.geo.longitude) };
            }
            else {
                _this.mapCenter = { lat: Number(_this.lat), lng: Number(_this.lng) };
            }
            console.log('mapCenter=', _this.mapCenter);
            _this.foundUser = true;
            _this.doSearchLocation(_this.searchLocation);
        }, function (err) {
            if (err.error instanceof Error) {
                console.log('doSearchUser client error=', err);
                _this.searchErrorMessage = err['error']['message'];
                _this.foundUser = false;
            }
            else {
                console.log('doSearchUser server error=', err);
                _this.searchErrorMessage = err['error']['message'];
                _this.foundUser = false;
            }
        });
    };
    /**
     * @param center - either null or an address
     */
    AppComponent.prototype.doSearchLocation = function (center) {
        var _this = this;
        console.log('reloading from Remote..., center=', center);
        this.searchErrorMessage = null;
        this.clearLocations();
        var items;
        var headers = new __WEBPACK_IMPORTED_MODULE_2__angular_common_http__["c" /* HttpHeaders */]()
            .set('X-Requested-With', 'XMLHttpRequest')
            .set('responseType', 'json');
        // console.log('added headers=', headers);
        var params = null;
        if (center) {
            params = new __WEBPACK_IMPORTED_MODULE_2__angular_common_http__["d" /* HttpParams */]().set('address', center)
                .set('radius', String(this.searchRadius))
                .set('limit', String(this.searchLimit));
            console.log('reload params=', params);
        }
        this.http.get(this.loadSearchLocation, { params: params, headers: headers })
            .subscribe(function (data) {
            console.log('remote data=', data);
            items = data;
            _this.users = items;
            // console.log('load items=', items);
            var firstItem = true;
            for (var _i = 0, items_1 = items; _i < items_1.length; _i++) {
                var item = items_1[_i];
                // console.log('insert new ', item);
                if (firstItem) {
                    _this.mapCenter = { lat: Number(item.geo.latitude), lng: Number(item.geo.longitude) };
                    firstItem = false;
                }
                _this.getIconUrl(item);
                _this.info = { id: item.id,
                    geo: { latitude: Number(item.geo.latitude),
                        longitude: Number(item.geo.longitude) },
                    display: true,
                    color: item.color,
                    forum_name: item.forum,
                    location: item.location,
                    iconUrl: item.iconUrl,
                    label: null,
                };
                if (item.geo.latitude) {
                    _this.positions.push({ latlng: [Number(item.geo.latitude), Number(item.geo.longitude)], item: item });
                }
                _this.selectOptions.push([item.id, item.forum, item.iconUrl]);
                // this.updateItem(item, true);
            }
            // map won't have markers yet, so wait a bit to set bounds
            _this.fitBounds(_this.map);
            // console.log('selectOptions=', this.selectOptions);
        }, function (err) {
            if (err.error instanceof Error) {
                console.log('doSearchUser client error=', err);
                _this.searchErrorMessage = err['error']['message'];
                _this.foundUser = false;
            }
            else {
                console.log('doSearchUser server error=', err);
                _this.searchErrorMessage = err['error']['message'];
                _this.foundUser = false;
            }
        });
    };
    AppComponent.prototype.onSliderChange = function (event) {
        console.log(event);
        console.log('radius=', this.searchRadius);
    };
    AppComponent.prototype.onLimitChange = function (event) {
        console.log(event);
        console.log('limit=', this.searchLimit);
    };
    AppComponent.prototype.markerClicked = function (event, marker) {
        // console.log('clicked marker event=', event, ', marker=', marker);  // marker is {latlng, item}
        this.infoWindow.geo = { latitude: event.target.getPosition().lat(),
            longitude: event.target.getPosition().lng() };
        this.infoWindow.forum_name = marker.item.forum;
        this.infoWindow.label = marker.item.label;
        this.infoWindow.location = marker.item.location;
        this.infoWindow.profileUrl = '/memberlist.php?mode=viewprofile&u=' + marker.item.id;
        this.infoWindow.display = true;
        console.log('info=', this.infoWindow);
        this.mapCenter = { lat: Number(this.infoWindow.geo.latitude), lng: Number(this.infoWindow.geo.longitude) };
        // console.log('infoWindows=', event.target.nguiMapComponent.infoWindows);
        event.target.nguiMapComponent.openInfoWindow('iw-user', event.target);
    };
    AppComponent.prototype.getIconUrl = function (item) {
        item.label = {
            fontFamily: 'Fontawesome',
            text: '\uf041',
            'font-size': '64px',
            color: '#' + item.color
        };
        item.icon = {
            path: google.maps.SymbolPath.CIRCLE,
            scale: 8,
            strokeOpacity: 0.05
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
    AppComponent = __decorate([
        Object(__WEBPACK_IMPORTED_MODULE_0__angular_core__["n" /* Component */])({
            selector: 'app-gm',
            styles: [__webpack_require__("../../../../../src/app/app.component.css")],
            template: "\n          <h1>Forum User Locations</h1>\n          <div>\n            <div><h2 *ngIf=\"searchErrorMessage\" >Search error {{ searchErrorMessage }}</h2>\n            <mat-form-field>\n                <input matInput [(ngModel)]=\"searchUser\" placeholder=\"User forum name\">\n                <mat-error *ngIf=\"searchErrorMessage\">{{searchErrorMessage}}</mat-error>\n            </mat-form-field>\n            <div  class=\"button-row\">\n            <button mat-raised-button color=\"primary\"\n                    (click)=\"doSearchUser()\" [disabled]=\"!searchUser || !searchRadius\">\n                Search by forum user name\n            </button>\n            </div>\n            <mat-form-field>\n                <input matInput [(ngModel)]=\"searchLocation\" placeholder=\"Search location\">\n                <mat-error *ngIf=\"searchErrorMessage\">{{searchErrorMessage}}</mat-error>\n            </mat-form-field>\n            <div  class=\"button-row\">\n            <button mat-raised-button color=\"primary\"\n                    (click)=\"doSearchLocation(this.searchLocation)\" [disabled]=\"!searchLocation || !searchRadius\">\n                Search by location\n            </button>\n            </div>\n            <div>\n             Radius(km)={{ searchRadius }}\n            <mat-slider [(ngModel)]=\"searchRadius\" (input)=\"this.onSliderChange($event)\"\n                min=\"100\" max=\"20000\" step=\"100\" value=\"200\">\n            </mat-slider>\n            </div>\n            <div>\n             Limit to {{ searchLimit }} closest:\n            <mat-slider [(ngModel)]=\"searchLimit\" (input)=\"this.onLimitChange($event)\"\n                min=\"10\" max=\"100\" step=\"10\" value=\"20\">\n            </mat-slider>\n            </div>\n            <h2>Search near selected user</h2>\n            <div>\n                <mat-form-field *ngIf=\"selectOptions\">\n                    <mat-select placeholder=\"Pick a user\" name=\"selectUser\"\n                        [(ngModel)]=\"selectedLocation\" (selectionChange)=\"showLocation()\">\n                        <mat-option *ngFor=\"let opt of selectOptions\" [value]=\"opt[0]\">\n                            {{ opt[1] }}\n                        </mat-option>\n                    </mat-select>\n                </mat-form-field>\n            </div>\n          <ngui-map center=\"{{ mapCenter }}\"\n             (mapReady$)=\"onInit($event)\"\n             (idle)=\"onIdle($event)\"\n            [zoom]=\"3\"\n            [zoomControlOptions]=\"{position: 'TOP_CENTER'}\"\n            [fullscreenControl]=\"true\"\n            [fullscreenControlOptions]=\"{position: 'TOP_CENTER'}\"\n            (click)=\"log($event)\"\n            [scrollwheel]=\"false\">\n            <marker *ngFor=\"let pos of positions\" [position]=\"pos.latlng\"\n                    [icon]=\"pos.item.icon\" [label]=\"pos.item.label\"\n                     (click)=\"markerClicked($event, pos)\">\n            </marker>\n            <info-window id=\"iw-user\">\n                <div *ngIf=\"infoWindow.display\">\n                    <a href=\"{{ infoWindow.profileUrl }}\">{{ infoWindow.forum_name }} @ {{ infoWindow.location }}</a>\n                </div>\n            </info-window>\n          </ngui-map>          "
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