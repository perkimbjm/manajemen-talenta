import "./bootstrap";
import "./plugins";
import "./modal";
import "$/power-components/livewire-powergrid/dist/powergrid";
import "$/power-components/livewire-powergrid/dist/tailwind.css";
import flatpickr from "flatpickr";
import TomSelect from "tom-select";
import "flatpickr/dist/flatpickr.min.css";

window.flatpickr = flatpickr;
window.TomSelect = TomSelect;

window.addEventListener("kamus-kompetensi-url", (event) => {
        const detail = event?.detail ?? {};
        const { url, position_name: positionName, api_type: apiType } = detail;

        console.groupCollapsed("Kamus Kompetensi API Call");
        console.log("URL:", url);
        console.log("Position Name:", positionName);
        console.log("API Type:", apiType);
        console.groupEnd();
});
