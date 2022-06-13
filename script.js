console.info("script loaded");
jQuery(document).ready(function ($) {
  function initMap() {
    const map = new google.maps.Map(document.getElementById("map"), {
      zoom: 9,
      center: { lat: 39.786546180936845, lng: -86.15745939017197 },
    });

    var district = {
      cd: "",
      sd: "",
      hd: "",
      cb: "",
    };

    // alert("search yout address and select district");

    const congressional = new google.maps.KmlLayer({
      url: "https://dev.indianacitizen.org/wp-content/plugins/sb-search-candidate/google-kml/2022-congressional-districts.kmz",
      map: map,
      suppressInfoWindows: true,
      preserveViewport: false,
    });
    congressional.setMap(map);
    const state_senate = new google.maps.KmlLayer({
      url: "https://dev.indianacitizen.org/wp-content/plugins/sb-search-candidate/google-kml/state-senate-districts.kmz",
      map: map,
      suppressInfoWindows: true,
      preserveViewport: false,
    });
    state_senate.setMap(null);
    const house_district = new google.maps.KmlLayer({
      url: "https://www.google.com/maps/d/u/0/kml?mid=15zexmB-Qq9u49lnJOuKpTrXpymxNdb9X&lid=GdHEO0r_pFw",
      map: map,
      suppressInfoWindows: true,
      preserveViewport: false,
    });
    house_district.setMap(null);
    const county_boundary = new google.maps.KmlLayer({
      url: "https://dev.indianacitizen.org/wp-content/plugins/sb-search-candidate/google-kml/indiana-county-boundaries.kmz",
      map: map,
      suppressInfoWindows: true,
      preserveViewport: false,
    });
    county_boundary.setMap(null);

    congressional.addListener("click", function (kmlEvent) {
      console.log(kmlEvent.featureData);

      if (district.cd == "") {
        $("#cd-check .fa").removeClass("fa-circle-o");
        $("#cd-check").addClass("d-green");
        $("#cd-check .fa").addClass("fa-check-circle");
        district.cd = kmlEvent.featureData.name;
        console.log(district.cd);
        congressional.setMap(null);
        state_senate.setMap(map);
        alert("select State Senate district");
      }
    });

    state_senate.addListener("click", function (kmlEvent) {
      console.log(kmlEvent.featureData);

      if (district.sd == "") {
        // var dCheck = document.getElementById('sd-check');
        // dCheck.classList.add("d-green");
        $("#sd-check .fa").removeClass("fa-circle-o");
        $("#sd-check").addClass("d-green");
        $("#sd-check .fa").addClass("fa-check-circle");
        district.sd = kmlEvent.featureData.name;
        console.log(district.sd);
        state_senate.setMap(null);
        house_district.setMap(map);
        alert("select State House district");
      }
    });

    house_district.addListener("click", function (kmlEvent) {
      console.log(kmlEvent.featureData);

      if (district.hd == "") {
        $("#hd-check .fa").removeClass("fa-circle-o");
        $("#hd-check").addClass("d-green");
        $("#hd-check .fa").addClass("fa-check-circle");
        house_district.setMap(null);
        county_boundary.setMap(map);
        district.hd = kmlEvent.featureData.name;
        console.log(district.hd);
        alert("select County Boundaries");
      }
    });

    county_boundary.addListener("click", function (kmlEvent) {
      // if(district.cb == ''){
      $("#cb-check .fa").removeClass("fa-circle-o");
      $("#cb-check").addClass("d-green");
      $("#cb-check .fa").addClass("fa-check-circle");
      county_boundary.setMap(null);
      district.cb = kmlEvent.featureData.name;
      console.log(district.cb);
      //console.log(window.location.href);

      // }
      // else
      // {
      // https://dev.indianacitizen.org/virtualballot-google-csv-v2/?sd=23&cd=7&hd=7&cb=Wayne%20County
      district.cb = kmlEvent.featureData.name;
      var link = window.location.href;
      // var url = new URL(
      //     "https://dev.indianacitizen.org/virtualballot-google-csv-v2/"
      // );
      var url = new URL(link);
      var search_params = url.searchParams;

      search_params.set("cd", district.cd);
      search_params.set("sd", district.sd);
      search_params.set("hd", district.hd);
      search_params.set("cb", district.cb);

      url.search = search_params.toString();

      location.href = url.toString();
      // }
    });

    // function showInContentWindow(text) {
    //     var sidediv = document.getElementById('content-window');
    //     sidediv.innerHTML = text;
    // }

    var searchBox = new google.maps.places.SearchBox(
      document.getElementById("pac-input")
    );
    map.controls[google.maps.ControlPosition.TOP_CENTER].push(
      document.getElementById("pac-input")
    );

    google.maps.event.addListener(searchBox, "places_changed", function () {
      searchBox.set("map", null);

      var places = searchBox.getPlaces();

      var bounds = new google.maps.LatLngBounds();
      var i, place;
      for (i = 0; (place = places[i]); i++) {
        (function (place) {
          var marker = new google.maps.Marker({
            position: place.geometry.location,
          });

          marker.bindTo("map", searchBox, "map");

          google.maps.event.addListener(marker, "map_changed", function () {
            if (!this.getMap()) {
              this.unbindAll();
            }
          });
          bounds.extend(place.geometry.location);
        })(place);
      }
      map.fitBounds(bounds);
      searchBox.set("map", map);
      map.setZoom(Math.min(map.getZoom(), 9));
    });
  }

  //        window.initMap = initMap;
  google.maps.event.addDomListener(window, "load", initMap);

  // office category in my representative....
  $("#ofc_cat").on("change", function () {
    if (this.value == "") {
      $("show_hide").show();
    } else {
      $(".show_hide").hide();
      $("." + this.value).show();
    }
  });

  // for sorting incumbent
  $(".incum1").prependTo(".uss");
  $(".incum2").prependTo(".sos");
  $(".incum3").prependTo(".us_representativee");
  $(".incum4").prependTo(".sb_state_senator");
  $(".incum5").prependTo(".sb_house_dist");
  $(".incum6").prependTo(".sb_pros_attorn");
  $(".incum7").prependTo(".sb_cb_ca");
  $(".incum8").prependTo(".sb_cb_cs");
  $(".incum9").prependTo(".sb_cb_cau");
  $(".incum10").prependTo(".sb_cb_cc");
  $(".incum11").prependTo(".sb_cb_csur");
  $(".incum12").prependTo(".sb_cb_ct");
  $(".incum13").prependTo(".sb_cb_cco");
  $(".incum14").prependTo(".sb_cb_cr");
  $(".incum15").prependTo(".sb_cb_jcc");
  $(".incum16").prependTo(".sb_cb_jsmc");
  $(".incum17").prependTo(".sb_cb_jsc");
  
});
