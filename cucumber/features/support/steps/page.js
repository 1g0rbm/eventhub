const { Given, Then, When } = require('@cucumber/cucumber')
const { expect } = require('chai')

const open = async function (uri) {
  await this.page.goto('http://gateway:8080' + uri)
}

Given('I am on {string} page', { wrapperOptions: { retry: 2 }, timeout: 30000 }, open)

When('I open {string} page', { wrapperOptions: { retry: 2 }, timeout: 30000 }, open)

Then('I see {string}', async function (value) {
  await this.page.waitForFunction(
    (text) => document.querySelector('body').innerText.includes(text),
    {},
    value
  )
})

Then('I do not see {string}', async function (value) {
  const content = await this.page.content()
  expect(content).to.not.include(value)
})

Then('I see {string} element', async function (id) {
  await this.page.waitForSelector(`[data-test=${id}]`)
})

Then('I click {string} element', async function (id) {
  await this.page.click(`[data-test=${id}]`)
})

Then('I see {string} header', async function (value) {
  await this.page.waitForFunction((text) => {
    const el = document.querySelector('h1')
    return el ? el.innerText.includes(text) : false
  },
  {},
  value
  )
})
