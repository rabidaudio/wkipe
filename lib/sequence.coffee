
# convert a number into a unique subdomain, as short as possible.
# Should go a-9 and then aa,ab ... 98, 99 and then aaa, etc.

PowerRadix = require 'power-radix'

base = "abcdefghijklmnopqrstuvwxyz0123456789".split("")

encode = (index) -> new PowerRadix(index, 10).toString(base)

decode = (code) -> parseInt(new PowerRadix(code, base).toString(10), 10)

# index 29326 is 'www', which is reserved
special = decode 'www'
encodeIndex = (index) -> if index >= special then index + 1 else index
decodeIndex = (index) -> if index >= special then index - 1 else index

module.exports = {
  encode: (index) -> encode encodeIndex index

  decode: (code) -> decodeIndex decode code
}