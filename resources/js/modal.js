import { once } from "./plugins/event";

window.WireElementModal = () => {
	return {
		show: false,
		loading: false,
		closing: false,
		title: "",
		showActiveComponent: true,
		activeComponent: false,
		componentHistory: [],
		modalWidth: null,
		listeners: [],
		modal: null,
		getActiveComponentModalAttribute(key) {
			if (this.$wire.get("components")[this.activeComponent] !== undefined) {
				return this.$wire.get("components")[this.activeComponent]
					.modalAttributes[key];
			}
		},
		closeModal(force = false, skipPreviousModals = 0, destroySkipped = false) {
			if (this.show === false) {
				return;
			}

			if (this.componentHistory.length <= skipPreviousModals + 1) {
				this.modal.close();
				this.show = false;

				setTimeout(() => {
					Livewire.dispatch("destroyComponent", { id: this.activeComponent });
					this.componentHistory.pop();
					this.setShowPropertyTo(false);
				}, 150);

				return;
			}

			const componentName =
				this.$wire.get("components")[this.activeComponent].name;
			Livewire.dispatch("modalClosed", { name: componentName });

			this.componentHistory.pop();
			const id = this.componentHistory.at(-1);
			Livewire.dispatch("destroyComponent", { id: this.activeComponent });

			if (id) {
				this.setActiveModalComponent(id, true);
			} else {
				this.setShowPropertyTo(false);
			}
		},
		setActiveModalComponent(id, skip = false) {
			if (this.activeComponent === id) {
				return;
			}

			this.activeComponent = id;

			const component = this.$wire.get("components")[this.activeComponent];

			this.title = component.modalAttributes.title;

			if (id && !this.componentHistory.includes(id)) {
				this.componentHistory.push(id);
			}
			this.modalWidth = this.getActiveComponentModalAttribute("maxWidthClass");
		},
		setShowPropertyTo(show) {
			if (show) {
				this.show = show;
				this.modal.showModal();
			} else {
				this.activeComponent = false;
				this.closing = true;
				setTimeout(() => {
					this.show = false;
					this.modal.close();
					this.$wire.resetState();
					this.modal.removeAttribute("close");
					this.closing = false;
				}, 150);
			}
		},
		init() {
			this.modal = this.$el.querySelector("dialog.modal");
			this.modalWidth = this.getActiveComponentModalAttribute("maxWidthClass");

			this.listeners.push(
				Livewire.on("closeModal", (data) => {
					this.closeModal(
						data?.force ?? false,
						data?.skipPreviousModals ?? 0,
						data?.destroySkipped ?? false,
					);
				}),
			);

			const originalShowModal = this.modal.showModal;

			this.modal.showModal = () => {
				originalShowModal.call(this.modal);
				if (!this.show) {
					this.show = true;
				}
				this.modal.dispatchEvent(new CustomEvent("open"));
				this.$nextTick(() => {
					this.modal.setAttribute("open", "active");
				});
			};

			this.listeners.unshift(
				Livewire.on("openModal", async (data) => {
					this.loading = true;
					this.setShowPropertyTo(true);
					await once(window, "modal-rendered", 2000);
					this.loading = false;
				}),
			);

			this.listeners.unshift(
				Livewire.on("activeModalComponentChanged", ({ id }) => {
					this.setActiveModalComponent(id);
				}),
			);
		},
		destroy() {
			this.listeners.map((listener) => {
				listener();
			});
		},
	};
};
