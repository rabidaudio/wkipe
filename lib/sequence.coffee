
# convert a number into a unique subdomain, as short as possible.d\
# Should go a-9 and then aa,ab ... 98, 99 and then aaa, etc.

###
   0 => a
   2 => c
  35 => 9
  36 => aa
  37 => ab
  72 => ba
  73 => bb
  1295 => 99
  1296 => aaa


TODO maybe should use base encode library?


36
  a = 0
  r = [a]
  i = 36/36 = 1
1
  a = 1
###

PowerRadix = require 'power-radix'

base = "abcdefghijklmnopqrstuvwxyz0123456789".split("")

encode = (index) -> new PowerRadix(index, 10).toString(base)

decode = (code) -> parseInt(new PowerRadix(code, base).toString(10), 10)

# index 29326 is 'www', which is reserved
special = decode 'www'
encodeIndex = (index)-> if index >= special then index+1 else index

decodeIndex = (index)-> if index >= special then index-1 else index

module.exports = {
  encode: (index) -> encode encodeIndex index

  decode: (code) -> decodeIndex decode code
}


  # return chars[0] if index is 0

  # char_size = chars.length
  # result = []
  # while index > 0
  #   a = index % char_size
  #   result.unshift chars[a]
  #   index -= index % char_size
  #   index = (index / char_size)

  # result.join("")




###
require('coffee-script/register');
seq = require('./lib/sequence');

a = [
  seq.encode(0),
  seq.encode(1),
  seq.encode(2),
  seq.encode(35),
  seq.encode(36),
  seq.encode(37),
  seq.encode(71),
  seq.encode(72),
  seq.encode(73),
  seq.encode(1294),
  seq.encode(1295),
  seq.encode(1296),
  seq.encode(29325),
  seq.encode(29326),
  seq.encode(29327)
];
console.log(a);
console.log(a.map(function(x){ return seq.decode(x);}));
###