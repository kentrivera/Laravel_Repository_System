import './bootstrap';

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

window.Alpine = Alpine;
window.Swal = Swal;

document.addEventListener('alpine:init', () => {
	Alpine.data('adminShell', () => ({
		sidebarOpen: false,
		sidebarCollapsed: false,

		init() {
			try {
				this.sidebarCollapsed = JSON.parse(localStorage.getItem('sidebarCollapsed') ?? 'false');
			} catch {
				this.sidebarCollapsed = false;
			}
		},

		toggleSidebar() {
			this.sidebarOpen = !this.sidebarOpen;
		},

		closeSidebar() {
			this.sidebarOpen = false;
		},

		toggleCollapse() {
			this.sidebarCollapsed = !this.sidebarCollapsed;
			localStorage.setItem('sidebarCollapsed', JSON.stringify(this.sidebarCollapsed));
		},
	}));
});

Alpine.start();

// Flash notifications (server-side -> client-side)
if (window.__FLASH_STATUS__) {
	Swal.fire({
		toast: true,
		position: 'top-end',
		icon: 'success',
		title: window.__FLASH_STATUS__,
		showConfirmButton: false,
		timer: 3500,
		timerProgressBar: true,
	});
}

// Confirm-delete handling (replaces window.confirm)
document.addEventListener('submit', async (event) => {
	const form = event.target;
	if (!(form instanceof HTMLFormElement)) return;

	if (!form.matches('[data-confirm="delete"]')) return;

	event.preventDefault();

	const result = await Swal.fire({
		title: 'Delete this item?',
		text: 'This action cannot be undone.',
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Delete',
	});

	if (result.isConfirmed) {
		form.submit();
	}
});
