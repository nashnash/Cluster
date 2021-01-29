import getLocation from'./utils/geoloc';

console.log(navigator.geolocation.getCurrentPosition(function(position) {
    console.log(position);
}))

console.log(getLocation.getLocation());