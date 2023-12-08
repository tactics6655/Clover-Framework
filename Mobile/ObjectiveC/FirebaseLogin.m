#import "FirebaseLogin.h"
#import <WebKit/WebKit.h>
#import <NaverThirdPartyLogin/NaverThirdPartyLogin.h>
#import <UserNotifications/UserNotifications.h>

@import CommonCrypto;

@implementation FirebaseLogin

@synthesize currentNonce;

NSString * currentNonce = nil;

+ (void) appleLogin:(id)selfObject {
    if (@available(iOS 13.0, *)) {
        [self startSignInWithAppleFlow:selfObject];
    }
}

+ (NSString *) getCurrentNonce {
    return currentNonce;
}

+ (void)startSignInWithAppleFlow :(id) selfObject API_AVAILABLE(ios(13.0)) {
    NSString *nonce = [self randomNonce:32];
    
    currentNonce = nonce;
    
    ASAuthorizationAppleIDProvider *appleIDProvider = [[ASAuthorizationAppleIDProvider alloc] init];
    ASAuthorizationAppleIDRequest *request = [appleIDProvider createRequest];
    request.requestedScopes = @[ASAuthorizationScopeFullName, ASAuthorizationScopeEmail];
    request.nonce = [self stringBySha256HashingString:nonce];
    
    ASAuthorizationController *authorizationController =
    [[ASAuthorizationController alloc] initWithAuthorizationRequests:@[request]];
    authorizationController.delegate = selfObject;
    authorizationController.presentationContextProvider = selfObject;
    [authorizationController performRequests];
}

+ (NSString *) stringBySha256HashingString :(NSString *)input {
    const char *string = [input UTF8String];
    unsigned char result[CC_SHA256_DIGEST_LENGTH];
    CC_SHA256(string, (CC_LONG)strlen(string), result);
    
    NSMutableString *hashed = [NSMutableString stringWithCapacity:CC_SHA256_DIGEST_LENGTH * 2];
    for (NSInteger i = 0; i < CC_SHA256_DIGEST_LENGTH; i++) {
        [hashed appendFormat:@"%02x", result[i]];
    }
    return hashed;
}

+ (NSString *) randomNonce :(NSInteger)length {
    NSAssert(length > 0, @"Expected nonce to have positive length");
    NSString *characterSet = @"0123456789ABCDEFGHIJKLMNOPQRSTUVXYZabcdefghijklmnopqrstuvwxyz-._";
    NSMutableString *result = [NSMutableString string];
    NSInteger remainingLength = length;
    
    while (remainingLength > 0) {
        NSMutableArray *randoms = [NSMutableArray arrayWithCapacity:16];
        for (NSInteger i = 0; i < 16; i++) {
            uint8_t random = 0;
            int errorCode = SecRandomCopyBytes(kSecRandomDefault, 1, &random);
            NSAssert(errorCode == errSecSuccess, @"Unable to generate nonce: OSStatus %i", errorCode);
            
            [randoms addObject:@(random)];
        }
        
        for (NSNumber *random in randoms) {
            if (remainingLength == 0) {
                break;
            }
            
            if (random.unsignedIntValue < characterSet.length) {
                unichar character = [characterSet characterAtIndex:random.unsignedIntValue];
                [result appendFormat:@"%C", character];
                remainingLength--;
            }
        }
    }
    
    return result;
}

@end
