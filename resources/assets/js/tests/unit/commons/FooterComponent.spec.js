import { createLocalVue, shallowMount } from '@vue/test-utils'
import myComponent from '@/components/commons/FooterComponent.vue'

window.api_url =  "http://dev.garafy.es";

const testModule = "commons::";
let wrapper = null;

afterEach(() => {
    wrapper.destroy();
});

describe(testModule + 'FooterComponent', () => {
    test("component's year is current year", () => {
        wrapper = shallowMount(myComponent);
        let year = new Date().getFullYear();
        expect(wrapper.vm.$data.currentYear).toEqual(year);
    })

})