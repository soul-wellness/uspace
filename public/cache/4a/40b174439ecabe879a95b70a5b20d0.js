/*!
FullCalendar v5.5.0
Docs & License: https://fullcalendar.io/
(c) 2020 Adam Shaw
*/
var FullCalendarLuxon=function(e,t,r){"use strict";var n=function(e,t){return(n=Object.setPrototypeOf||{__proto__:[]}instanceof Array&&function(e,t){e.__proto__=t}||function(e,t){for(var r in t)Object.prototype.hasOwnProperty.call(t,r)&&(e[r]=t[r])})(e,t)};var o=function(){return(o=Object.assign||function(e){for(var t,r=1,n=arguments.length;r<n;r++)for(var o in t=arguments[r])Object.prototype.hasOwnProperty.call(t,o)&&(e[o]=t[o]);return e}).apply(this,arguments)};var a=function(e){function t(){return null!==e&&e.apply(this,arguments)||this}return function(e,t){function r(){this.constructor=e}n(e,t),e.prototype=null===t?Object.create(t):(r.prototype=t.prototype,new r)}(t,e),t.prototype.offsetForArray=function(e){return l(e,this.timeZoneName).offset},t.prototype.timestampToArray=function(e){return[(t=r.DateTime.fromMillis(e,{zone:this.timeZoneName})).year,t.month-1,t.day,t.hour,t.minute,t.second,t.millisecond];var t},t}(t.NamedTimeZoneImpl);var i=t.createPlugin({cmdFormatter:function(e,t){var r=function e(t){var r=t.match(/^(.*?)\{(.*)\}(.*)$/);if(r){var n=e(r[2]);return{head:r[1],middle:n,tail:r[3],whole:r[1]+n.whole+r[3]}}return{head:null,middle:null,tail:null,whole:t}}(e);if(t.end){var n=l(t.start.array,t.timeZone,t.localeCodes[0]),o=l(t.end.array,t.timeZone,t.localeCodes[0]);return function e(t,r,n,o){if(t.middle){var a=r(t.head),i=e(t.middle,r,n,o),l=r(t.tail),u=n(t.head),c=e(t.middle,r,n,o),d=n(t.tail);if(a===u&&l===d)return a+(i===c?i:i+o+c)+l}var f=r(t.whole),m=n(t.whole);if(f===m)return f;return f+o+m}(r,n.toFormat.bind(n),o.toFormat.bind(o),t.defaultSeparator)}return l(t.date.array,t.timeZone,t.localeCodes[0]).toFormat(r.whole)},namedTimeZonedImpl:a});function l(e,t,n){return r.DateTime.fromObject({zone:t,locale:n,year:e[0],month:e[1]+1,day:e[2],hour:e[3],minute:e[4],second:e[5],millisecond:e[6]})}return t.globalPlugins.push(i),e.default=i,e.toLuxonDateTime=function(e,n){if(!(n instanceof t.CalendarApi))throw new Error("must supply a CalendarApi instance");var o=n.getCurrentData().dateEnv;return r.DateTime.fromJSDate(e,{zone:o.timeZone,locale:o.locale.codes[0]})},e.toLuxonDuration=function(e,n){if(!(n instanceof t.CalendarApi))throw new Error("must supply a CalendarApi instance");var a=n.getCurrentData().dateEnv;return r.Duration.fromObject(o(o({},e),{locale:a.locale.codes[0]}))},Object.defineProperty(e,"__esModule",{value:!0}),e}({},FullCalendar,luxon);