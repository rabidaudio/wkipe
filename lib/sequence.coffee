
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

chars = "abcdefghijklmnopqrstuvwxyz0123456789".split("")

module.exports = (index) ->
  return chars[0] if index is 0

  char_size = chars.length
  result = []
  while index > 0
    a = index % char_size
    result.unshift chars[a]
    index -= index % char_size
    index = (index / char_size)

  result.join("")




###
require('coffee-script/register');
seq = require('./lib/sequence');

console.log([
  seq(0),
  seq(1),
  seq(2),
  seq(35),
  seq(36),
  seq(37),
  seq(71),
  seq(72),
  seq(73),
  seq(1294),
  seq(1295),
  seq(1296)
])
###