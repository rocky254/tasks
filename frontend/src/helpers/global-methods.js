export default {
    scrollToElement(el, duration = 700) {
        let scrollTop = el.offsetTop - this.navbarHeight;
        this.$vuetify.goTo(scrollTop, { duration: duration, container: document.documentElement, easing: 'easeInOutQuad' });
    },
    navigateToLink(ref) {
        if (this.$route.name === 'home' || (this.$route.name === 'founder' && ref != '#headerSection')) {
            let element = document.querySelector(ref);
            if (element) {
                this.scrollToElement(element, 1000);
            }
        } else {
            this.$router.push({ name: 'home' });
        }
    },
    scrollToTop() {
        this.$vuetify.goTo(0, { duration: 1000, container: document.documentElement, easing: 'easeOutQuint' });
    },
    goToBookingPage() {
        this.$store.dispatch("SET_LIGHT_NAVBAR", true);
        this.$router.push('/booking');
    },
    goToAccountSettings() {
        this.$router.push('/account-settings');
    },
    handleLogout() {
        this.$store.dispatch("auth/LOGOUT");
        this.$store.dispatch("alert/SUCCESS", "Logged Out Successfully!");
        this.$router.push("/login");
    },
    validate() {
        return this.$refs.form.validate();
    },
    reset() {
        return this.$refs.form.reset();
    },
    resetValidation() {
        return this.$refs.form.resetValidation();
    },
    formatDate(date) {
        if (!date) return null;
        const [year, month, day] = date.split("-");
        return `${day}-${month}-${year}`;
    },
    reverseFormatDate(date) {
        if (!date) return null;
        const [day, month, year] = date.split("-");
        return `${year}-${month}-${day}`;
    },
    statusColor(status) {
        let color = '';
        switch (status) {
            case 1:
                color = 'info';
                break;
            case 2:
                color = 'success';
                break;
            case 3:
                color = 'error';
                break;
            case 4:
                color = 'warning';
                break;
            default:
                color = 'primary';
                break;
        }

        return color;
    },
    getStatusName(status, items) {
        if (items.length > 0) {
            let item = items.find((item) => item.id == status);
            return item.name;
        }
    },
    filterText(value, search) {
        return (
            value != null && search != null && typeof value === "string" &&
            value.toString().toLocaleLowerCase().indexOf(search) !== -1
        );
    },
}