<template>
  <div class="flex flex-col lg:flex-row gap-6">

    <!-- LEFT SIDE -->
    <div class="w-full lg:w-1/2">

      <!-- Address -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
          Address <span class="text-red-500">*</span>
        </label>

        <div class="relative flex items-center">
          <input
            ref="addressInput"
            type="text"
            v-model="localAddress"
            placeholder="Enter a location"
            class="w-full
                   border border-gray-300
                   rounded
                   pl-3 pr-10 py-2
                   text-sm
                   text-gray-800
                   focus:outline-none
                   focus:border-blue-400"
            style="box-sizing: border-box;"
          />

          <input type="hidden" :name="fieldName" :value="localAddress" />

          <!-- Search Icon — sits inside the input on the right -->
          <button
            type="button"
            @click="codeAddress"
            class="absolute right-0 inset-y-0
                   flex items-center justify-center
                   w-10
                   text-gray-400 hover:text-gray-600
                   focus:outline-none"
            style="background: transparent; border: none; cursor: pointer;"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="15"
              height="15"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
          </button>
        </div>
      </div>

    </div>

    <!-- RIGHT SIDE (Map) -->
    <div class="w-full lg:w-1/2">
      <div
        ref="mapCanvas"
        class="w-full border border-gray-300 rounded"
        style="height: 250px;"
      ></div>
    </div>

  </div>
</template>

<script>
export default {
  props: {
    address: String,
    latitude: Number,

    longitude: Number,
    fieldName: {
      type: String,
      default: 'address'
    }

  },

  emits: ['update:address', 'update:latitude', 'update:longitude'],

  data() {
    return {
      map: null,
      marker: null,
      geocoder: null,
      autocomplete: null,
      localAddress: this.address || ''
    };
  },

  async mounted() {
    await this.waitForGoogle();
    await this.initMap();
  },

  methods: {

    waitForGoogle() {
      return new Promise((resolve) => {
        const check = () => {
          if (window.google && window.google.maps) {
            resolve();
          } else {
            setTimeout(check, 300);
          }
        };
        check();
      });
    },

    async initMap() {
      const { Map } = await google.maps.importLibrary("maps");
      const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

      const lat = this.latitude || 9.9252007;
      const lng = this.longitude || 78.1197754;

      const center = { lat, lng };

      this.map = new Map(this.$refs.mapCanvas, {
        zoom: 15,
        center,
        mapId: "DEMO_MAP_ID"
      });

      this.marker = new AdvancedMarkerElement({
        map: this.map,
        position: center,
        gmpDraggable: true
      });

      this.geocoder = new google.maps.Geocoder();

      this.marker.addListener("dragend", (event) => {
        this.emitLocation(event.latLng.lat(), event.latLng.lng());
      });

      this.autocomplete = new google.maps.places.Autocomplete(
        this.$refs.addressInput
      );

      this.autocomplete.addListener("place_changed", () => {
        const place = this.autocomplete.getPlace();
        if (!place.geometry) return;

        this.localAddress = place.formatted_address;

        this.emitLocation(
          place.geometry.location.lat(),
          place.geometry.location.lng()
        );
      });
    },

    emitLocation(lat, lng) {
      this.map.setCenter({ lat, lng });
      this.marker.position = { lat, lng };

      this.$emit('update:latitude', lat);
      this.$emit('update:longitude', lng);
      this.$emit('update:address', this.localAddress);
    },

    codeAddress() {
      if (!this.geocoder || !this.localAddress) return;

      this.geocoder.geocode(
        { address: this.localAddress },
        (results, status) => {
          if (status === "OK") {
            const lat = results[0].geometry.location.lat();
            const lng = results[0].geometry.location.lng();
            
            this.localAddress = results[0].formatted_address;
            this.emitLocation(lat, lng);
          }
        }
      );
    }
  }
};
</script>