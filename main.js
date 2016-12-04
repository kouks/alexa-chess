'use strict';

setInterval(() => {
  $.get('http://chess.pavelkoch.me/chess', (data) => {
    movePieces(JSON.parse(data));
  });
}, 1500);
  
function movePieces(pieces) {
  for (let i = 0; i < 8; i++) {
    for (let j = 0; j < 8; j++) {
      let $tile = $(`#${j}-${i}`);

      $tile.removeClass((index, css) => {
        return (css.match(/pc-\w+/g) || []).join(' ');
      });

      if (pieces[j][i] === 'x') {
        continue;
      }

      $tile.addClass(`pc-${pieces[j][i]}`);
    }
  }
}
