var kundenTable = new Vue({
    el: '#kunden-table',
    data() {
        return {
            items: null,
            companyBackup: null, //original DB for resets
            fields: [{
                key: 'kd_id',
                label: 'ID',
                sortable: true
            }, {
                key: 'kd_nr',
                label: 'Nr',
                sortable: true
            }, {
                key: 'kd_ansprechpartner',
                label: 'Ansprechpartner',
                sortable: true
            }, {
                key: 'kd_reseller',
                label: 'Reseller',
                sortable: true
            }, {
                key: 'kd_kommentar',
                label: 'Kommentar',
                sortable: true
            }, {
                key: 'com_id',
                label: 'Company ID',
                sortable: true
            }],
            filter: null
        }
    },
    mounted() {
        axios
            .get('./PHPs/fetch_kunden.php')
            .then(response => {
                this.items = response.data
                this.companyBackup = response.data
                return this.items, this.companyBackup
            })
    },
    methods: {
        filterFunction(row, filter) {
            return row.kd_id.includes(filter)
        }
    }
})

var companyTable = new Vue({
    el: '#company-table',
    data() {
        return {
            items: null,
            filter: null
        }
    },
    mounted() {
        axios
            .get('./PHPs/fetch_company.php')
            .then(response => (this.items = response.data))
    },
    methods: {
        //Changes the background color and searches the appropriate customers on click
        companyRowClickHandler(item, index) {
            //Reset the kundenTable to default state
            kundenTable.items = kundenTable.companyBackup
            licenceTable.items = licenceTable.companyBackup

            //Pseudo-Toggle
            if (item._rowVariant === undefined) {
                //Unselect all other rows
                this.items.forEach(item => {
                    this.$set(item, '_rowVariant', undefined)
                })
                //Select row
                this.$set(item, '_rowVariant', 'success')

                //create new KundenTable
                let newKundenTable = [];
                kundenTable.items.forEach(element => {
                    if (element.com_id === item.com_nr) {
                        newKundenTable.push(element)
                    }
                })
                kundenTable.items = newKundenTable;

                //create new LicenceTable
                let newLicenceTable = [];
                newKundenTable.forEach(kunde => {
                    licenceTable.items.forEach(licence => {
                        if (licence.liz_kd === kunde.kd_nr) {
                            newLicenceTable.push(licence)
                        }
                    })
                })
                licenceTable.items = newLicenceTable;
            } else {
                this.$set(item, '_rowVariant', undefined)
            }
        }
    }
})

var licenceTable = new Vue({
    el: '#licence-table',
    data() {
        return {
            items: null,
            companyBackup: null,
            kundenBackup: null,
            fields: ['liz_id', 'liz_kd', 'liz_datum', 'liz_valid_bis', 'liz_typ', 'liz_anzahl'],
            filter: null
        }
    },
    mounted() {
        axios
            .get('./PHPs/fetch_licences.php')
            .then(response => {
                this.items = response.data;
                this.companyBackup = response.data;
                this.kundenBackup = response.data;
                return this.items, this.companyBackup, this.kundenBackup;
            })
    },
    methods: {}
})