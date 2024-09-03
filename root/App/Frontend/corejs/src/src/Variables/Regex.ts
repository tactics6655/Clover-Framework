export const regInputs = /^(?:input|select|textarea|button)$/i;
export const regHeader = /^h\d$/i;
export const regNative = /^[^{]+\{\s*\[native \w/;
export const regQuickExpr = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/;
export const regSibling = /[+~]/;
export const regEscape = /'|\\/g;
export const regJapanese = /([ぁ-んァ-ヶー一-龠])/;
//export const regJapan = /^[\p{Katakana}\p{Hiragana}\p{Han}]+$/u;
export const regKanji = /[一-龠]$/;
export const regHiragana = /^([ぁ-ん]+)$/;
export const regKatakana = /^([ァ-ヶー]+)$/;
export const regHalfKana = /^([ｧ-ﾝﾞﾟ]+)$/;
export const regHiraganaKatakana = /^([ァ-ヶーぁ-ん]+)$/;
export const regFArrtype = /^\[object (?:Uint8|Uint8Clamped|Uint16|Uint32|Int8|Int16|Int32|Float32|Float64)Array]$/;
export const regURLParmas = /([^=&?]+)=([^&#]*)/g;
export const regRewriteParams = /^\/(.+)\/([A-Za-z0-9]*)$/;
export const regWhiteSpace = /^\s*$/;
export const regEmail = /^[^"'@]+@[._a-zA-Z0-9-]+\.[a-zA-Z]+$/;
export const regUrl = /^(http\:\/\/)*[.a-zA-Z0-9-]+\.[a-zA-Z]+$/;
export const regWords = /\w+/g;
export const regNum = /^[0-9]+$/;
export const regAlpha = /^[a-zA-Z]+$/;
export const regOnlyKor = /^[\uAC00-\uD7A3]*$/;
export const regKor = /[^\uAC00-\uD7AF\u1100-\u11FF\u3130-\u318F]/;
export const regPerfectKor = /[\uAC00-\uD7A3]/;
export const regKorEng = /[\uAC00-\uD7A3a-zA-Z]/;
export const regRRN = /^\d{6}[1234]\d{6}$/; //Jumin
export const regId = /#([\w\-]+)/;
export const regIds = /^#([\w\-]+)$/;
export const regClasss = /^\.([\w\-]+)$/;
export const regcanvas = /canvas/i;
export const regimg = /img/i;
export const reginput = /input/i;
export const regdata = /^data:[^;]+;/


export const regJapan = /([ぁ-んァ-ヶー一-龠])/;