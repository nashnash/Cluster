function showPosition(position) {
    console.log(position)
}

export default {
    getLocation() {
        if (navigator.geolocation) {
            console.log(navigator.geolocation.getCurrentPosition(showPosition))
            return navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            return "Geolocation is not supported by this browser.";
        }
    }
}