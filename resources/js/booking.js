window.bookingForm = function(pricePerDay, pricePerHour, bookedDates, initialMode) {
    return {
        pricingMode: initialMode === 'both' ? 'day' : initialMode,

        startDate: null,
        endDate: null,
        days: 0,

        bookingDate: null,
        startHour: '',
        endHour: '',
        hours: 0,

        totalPrice: 0,

        allTimeSlots: [
            '07:00','08:00','09:00','10:00','11:00','12:00',
            '13:00','14:00','15:00','16:00','17:00','18:00',
            '19:00','20:00','21:00','22:00'
        ],

        pickerStart: null,
        pickerEnd: null,
        pickerHourDate: null,

        init() {
            this.pickerStart = flatpickr(this.$refs.startInput, {
                dateFormat: 'Y-m-d',
                minDate: 'today',
                disable: bookedDates,
                disableMobile: true,
                onChange: (dates) => {
                    this.startDate = dates[0] || null;

                    if (this.endDate && this.startDate && this.endDate <= this.startDate) {
                        this.endDate = null;
                        this.pickerEnd.clear();
                    }

                    if (this.pickerEnd && this.startDate) {
                        const next = new Date(this.startDate);
                        next.setDate(next.getDate() + 1);
                        this.pickerEnd.set('minDate', next);
                    }

                    this.calculate();
                }
            });

            this.pickerEnd = flatpickr(this.$refs.endInput, {
                dateFormat: 'Y-m-d',
                minDate: 'today',
                disable: bookedDates,
                disableMobile: true,
                onChange: (dates) => {
                    this.endDate = dates[0] || null;
                    this.calculate();
                }
            });

            this.pickerHourDate = flatpickr(this.$refs.hourDateInput, {
                dateFormat: 'Y-m-d',
                minDate: 'today',
                disable: bookedDates,
                disableMobile: true,
                onChange: (dates) => {
                    this.bookingDate = dates[0] || null;
                    this.startHour = '';
                    this.endHour = '';
                    this.totalPrice = 0;
                    this.calculate();
                }
            });
        },

        get endTimeSlots() {
            if (!this.startHour) return [];
            return this.allTimeSlots.filter(t => t > this.startHour);
        },

        calculate() {
            if (this.pricingMode === 'day') {
                if (!this.startDate || !this.endDate) {
                    this.days = 0;
                    this.totalPrice = 0;
                    return;
                }
                this.days = Math.floor((this.endDate - this.startDate) / 86400000) + 1;
                this.totalPrice = this.days * pricePerDay;
            }

            if (this.pricingMode === 'hour') {
                if (!this.bookingDate || !this.startHour || !this.endHour) {
                    this.hours = 0;
                    this.totalPrice = 0;
                    return;
                }
                const start = new Date(`1970-01-01T${this.startHour}`);
                const end   = new Date(`1970-01-01T${this.endHour}`);
                const diff  = (end - start) / 3600000;

                if (diff <= 0) {
                    this.hours = 0;
                    this.totalPrice = 0;
                    return;
                }

                this.hours = diff;
                this.totalPrice = this.hours * pricePerHour;
            }
        },

        get bookingDateFormatted() {
            if (!this.bookingDate) return '';
            return this.bookingDate.toLocaleDateString('en-GB', {
                day: 'numeric', month: 'short', year: 'numeric'
            });
        },

        get summaryLabel() {
            if (this.pricingMode === 'day') {
                return `${this.days} day${this.days !== 1 ? 's' : ''}`;
            }
            return `${this.hours} hr${this.hours !== 1 ? 's' : ''}`;
        },

        get canSubmit() {
            if (this.pricingMode === 'day') return this.startDate && this.endDate;
            return this.bookingDate && this.startHour && this.endHour;
        }
    }
}

