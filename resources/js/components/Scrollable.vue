<template>
    <div>
        <slot></slot>
    </div>
</template>

<script>

    export default {

        mounted() {

                const eventHandler = () => {
                const scrollTop = document.documentElement.scrollTop
                const viewportHeight = window.innerHeight
                const totalHeight = document.documentElement.offsetHeight
                const atTheBottom = scrollTop + viewportHeight  == totalHeight
                console.log("scrollTop: "+scrollTop)
                console.log("viewPortHeight : "+viewportHeight)
                console.log("totalHeight: "+totalHeight)
                if (atTheBottom) {

                    this.$emit('at-the-bottom')


                }

            }

            let delayedHandler = _.debounce(eventHandler, 400)

            document.addEventListener('scroll', delayedHandler)
            this.$once('hook:destroyed', () => {
                document.removeEventListener('scroll', delayedHandler)
            })
        }
    }
</script>
