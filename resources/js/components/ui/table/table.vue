
<template>
    <table class="w-full text-sm text-left rtl:text-right text-body ">
        <thead class="text-sm text-body bg-neutral-50 border-b rounded-base border-default">
            <slot name="head"></slot> 
        </thead>
        <tbody>
            <slot name="body"></slot> 
        </tbody>
    </table>  
</template>