import { unicodeImoticons } from "./../Variables/Common";

export class StringService {

    getMinusOne () {
        return ~[]>>1; // -~[i+2]>>4<<[]
    }
	
    getImoticonItems () {
        var imoticons = (unicodeImoticons);

        return imoticons;
    }
    
    getImoticonItem (name: string) {
        var imoticons = this.getImoticonItems();
        
        if (typeof imoticons[name] === undefined) {
            return null;
        }
        
        return imoticons[name];
    }
    

    brToLine (str) {
        return str.replace(/<br([^>]*)>/ig, "\n");
    }
    
    lineToBr (str) {
        return str.replace(/(\r\n|\n|\r)/g, "<br />");
    }

    toLowerCase (str) {
        return str.toLowerCase();
    }

    ucFirst (str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    leftTrim (str) {
        return str.replace(/^\s+/,"");
    }
    
    trim (str) {
        return str.replace(/(^\s*)|(\s*$)/g, "");
    }
    
    rightTrim (str) {
        return str.replace(/\s+$/,"");
    }

    toUpperCase (str) { 
        return str.toUpperCase();
    }

    cut (str, limit, prefix = '...') {
        str.length > limit && (str = str.substring(0, limit - 3) + prefix);
        return str;
    }

    stripTags (str) {
        return str.replace(/(<([^>]+)>)/ig, '');
    }

    removeComma (num) {
        num = new String(num);
        return num.replace(/,/gi,"");
    }

    replaceToNewLine (str) {
        return str.replace(/\\n/g,'\n');
    }

    reverse (str) {
        return str.match(/(.)/g).reverse().join('');
    }

    isSmallThenValue (i, z) {
        var res = i << z % i >> z;

        if (res == z) {
            return true;
        }
        
        return false;
    }

    getRemainderByCount (root, count) {
        return (root + 2) % count;
    }

    bitCount (x) {
        /**
         *
         * 0x55 = 01010101
         * 0x33 = 00110011
         *
         */
         
        // Count bits of 2bit chunk
        x  = x - ((x >> 1) & 0x55555555); 
        // Count bits of 4bit chunk
        x  = (x & 0x33333333) + ((x >> 2) & 0x33333333);
        // Count bits of 8bit chunk
        x  = x + (x >> 4);
        
        // Mask out junk
        x &= 0xF0F0F0F;

        /**
         *    1000000000000000000000000 = 1 << 24
         *	  0000000010000000000000000 = 1 << 16
         *	  0000000000000000100000000 = 1 << 8
         *	+ 0000000000000000000000001 = 1
         *	  |-------|-------|-------|
         *	  1000000010000000100000001 = 16843009(0x01010101)
         */
        // Add all four 8bit chunks
        return (x * 0x01010101) >> 24;
    }

    a2hex (str) {
        var arr = [];
        
        for (var i = 0, l = str.length; i < l; i ++) {
            var hex = Number(str.charCodeAt(i)).toString(16);
            arr.push(hex);
        }
        
        return arr.join('');
    }

    text2Binary(string) {
        return string.split('').map(function (char) {
            return char.charCodeAt(0).toString(2);
        }).join(' ');
    }

    hex2a (hexx) {
        var hex = hexx.toString();
        var str = '';
        var sSize = 2;
        
        for (var i = 0; i < hex.length; i += sSize) {
            var hexCode = hex.substr(i, sSize);
            str += String.fromCharCode(parseInt(hexCode, 16));
        }
        
        return str;
    }

    determiningIntegerisPowOf2 (v: number) {
        v = v >>> 0; //convert to unsigned int
        
        return ((v & (v-1)) === 0) ? true : false;
    }

    hasOppositeSigns (x, y) {
        return (x ^ y) < 0 ? true : false;
    }

    isBlankExists (str) {
        if (str.indexOf(" ") != -1) {
            return true;
        }
        
        return false;
    }

    isIsothermal (str: number) {
        if ((3 & str) == 3) {
            return true;
        }
        
        return false;
    }

    isEvenNumber (str) {
        if ((1 & str) == 0 && ((1 & str) % str) == 0) {
            return true;
        }
        
        return false;
    }

    isOddNumber (str) {
        if ((1 & str) == 1 && ((1 & str) % str) == 0) {
            return true;
        }
        
        return false;
    }

    is4NP1 (str) {
        if ((3 & str) == 1 && (1 & str) % str == 0) {
            return true;
        }
        
        return false;
    }

    is2P6Px (str: number, x: number) {
        if ((5 & str) == x) {
            return true;
        }
        
        return false;
    }

    isKorCharFortisPos (chs) {
        if (697015 % (chs * 2 + 3) === 0) {
            return true;
        }
        
        return false;
    }

    dfsSimple (dta) {
        var dfsTemp = [];
        var dfsData = [];
        var vertical = dta[0].length;
        var horizon = dta.length;
        
        for (var i = 0;i < vertical; i++) {
            dfsTemp	= [];
            for (var z=0; z < horizon; z++) {
                var data = dta[z][i];
                if (!data) break;
                
                dfsTemp.push(data);
            }
            
            dfsData.push(dfsTemp);
        }
        
        return dfsData;
    }

    padBin (a, b) {
        if (a.length > b.length) {
            var pad = "0".repeat(a.length - b.length);
            b = pad + b;
        } else if (b.length > a.length) {
            var pad = "0".repeat(b.length - a.length);
            a = pad + a;
        }
        
        return [a, b];
    }

    leftMatchBin (a, b) {
        if (a.length > b.length) {
            var pad = "0".repeat(a.length - b.length);
            b = b + pad;
        } else if (b.length > a.length) {
            var pad = "0".repeat(b.length - a.length);
            a = a + pad;
        }
        
        return a;
    }
    
    xorBin (a, b) {
        var bTmp;
        var result = '';
        var i;
        
        b = b.split("").reverse().join(""); //reverse
        for (i = a.length -1; i > -1; i--) {
            var bPrefix = b.substr((((a.length -1) - b.length) - i) + b.length, 1);
            bTmp = bPrefix ? bPrefix + bTmp : 0 + bTmp;
        }
        
        for (i = a.length -1; i > -1; i--) {
            if (a.substr(i,1) == bTmp.substr(i,1)) {
                result = 0 + result;
            } else {
                result = 1 + result;
            }
        }
        
        return result;
    }
    
    orBin (a, b) {
        var bTmp;
        var result = '';
        var i;
        
        b = b.split("").reverse().join(""); //reverse
        for (i = a.length -1; i > -1; i--) {
            var bPrefix = b.substr((((a.length -1) - b.length) - i) + b.length, 1);
            bTmp = bPrefix ? bPrefix + bTmp : 0 + bTmp;
        }
        
        for (i = a.length -1; i > -1; i--) {
            if (a.substr(i,1) == 1 || bTmp.substr(i,1) == 1) {
                result = 1 + result;
            } else {
                result = 0 + result;
            }
            
        }
        
        return result;
    }
    
    andBin (a, b) {
        var bTmp;
        var result = '';
        var i;
        
        b = b.split("").reverse().join(""); //reverse
        for (i = a.length -1; i > -1; i--) {
            var bPrefix = b.substr((((a.length -1) - b.length) - i) + b.length, 1);
            bTmp = bPrefix ? bPrefix + bTmp : 0 + bTmp;
        }
        
        for (i = a.length -1; i > -1; i--) {
            if (a.substr(i,1) == bTmp.substr(i,1) && a.substr(i,1) == '1') {
                result = 1 + result;
            } else {
                result = 0 + result;
            }
            
        }
        
        return result;
    }
    
    addBin (a, b) {
        var bTmp;
        var result = "";
        var i;
        
        b = b.split("").reverse().join(""); //reverse
        for (i = a.length -1; i > -1; i--) {
            var bPrefix = b.substr((((a.length -1) - b.length) - i) + b.length, 1);
            bTmp = bPrefix ? bPrefix + bTmp : 0 + bTmp;
        }
        
        var carry: any = 0;
        for (i = a.length -1; i > -1; i--) {
            var prefix = bTmp.substr(i,1) ? (parseInt(a.substr(i,1)) + parseInt(bTmp.substr(i,1))) + parseInt(carry) : parseInt(a.substr(i,1)) + parseInt(carry);
            carry = prefix > 1 ? 1 : 0;
            result = prefix > 1 ? prefix == 2 ? 0 + result : 1 + result : prefix + result;
            if (i==0 && prefix > 1) result = 1 + result;
        }
        
        return result;
    }
}