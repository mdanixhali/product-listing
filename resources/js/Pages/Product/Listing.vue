<template></template>

<script>
export default {
    data() {
        return {
            product: {
                name: null,
                description: null,
                price: null,
                image: null
            },
            products: [],
            csrf: null,
        }
    },
    methods: {
        async listing() {
            fetch('https://dummyjson.com/products', {
                method: "GET",
                headers: {
                    "Content-type": 'application/json',
                },
            });
            const responseData = await response.json();
            this.products = responseData;
        },

        //After listing from given API, click on a button to store product into local DB. Can handle the updates adding a column in DB "isStored".
        //Based on that check, can perform update
        async store(product) {
            fetch(window.location.origin + '/product', {
                method: "POST",
                headers: {
                    "Content-type": 'application/json',
                    'X-CSRF-Token': this.csrf,
                },
                body: JSON.stringify(product),
            });
            const responseData = await response.json();
        },

        show(product) {
            this.product = product;
            //Show modal to see the product detail...
        }
    }
}
</script>